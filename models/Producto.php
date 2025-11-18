<?php
require_once __DIR__ . '/../config/database.php';

class Producto {
    private $conn;
    private $table = 'factura_items';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // Obtener todos los productos con info de factura
    public function getAll() {
        $query = "SELECT fi.*, f.numero_factura, f.cliente, f.fecha_emision 
                  FROM {$this->table} fi
                  INNER JOIN facturas f ON fi.factura_id = f.id
                  ORDER BY fi.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Obtener productos por factura
    public function getByFactura($factura_id) {
        $query = "SELECT * FROM {$this->table} WHERE factura_id = :factura_id ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':factura_id', $factura_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Obtener producto por ID
    public function getById($id) {
        $query = "SELECT fi.*, f.numero_factura 
                  FROM {$this->table} fi
                  INNER JOIN facturas f ON fi.factura_id = f.id
                  WHERE fi.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Crear nuevo producto
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (factura_id, codigo, cantidad, descripcion, laboratorio, lote, 
                   fecha_vencimiento, precio_unitario, importe) 
                  VALUES 
                  (:factura_id, :codigo, :cantidad, :descripcion, :laboratorio, :lote,
                   :fecha_vencimiento, :precio_unitario, :importe)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':factura_id', $data['factura_id']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':laboratorio', $data['laboratorio']);
        $stmt->bindParam(':lote', $data['lote']);
        $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
        $stmt->bindParam(':precio_unitario', $data['precio_unitario']);
        $stmt->bindParam(':importe', $data['importe']);
        
        return $stmt->execute();
    }
    
    // Actualizar producto
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                  factura_id = :factura_id,
                  codigo = :codigo,
                  cantidad = :cantidad,
                  descripcion = :descripcion,
                  laboratorio = :laboratorio,
                  lote = :lote,
                  fecha_vencimiento = :fecha_vencimiento,
                  precio_unitario = :precio_unitario,
                  importe = :importe
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':factura_id', $data['factura_id']);
        $stmt->bindParam(':codigo', $data['codigo']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':laboratorio', $data['laboratorio']);
        $stmt->bindParam(':lote', $data['lote']);
        $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
        $stmt->bindParam(':precio_unitario', $data['precio_unitario']);
        $stmt->bindParam(':importe', $data['importe']);
        
        return $stmt->execute();
    }
    
    // Eliminar producto
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Eliminar todos los productos de una factura
    public function deleteByFactura($factura_id) {
        $query = "DELETE FROM {$this->table} WHERE factura_id = :factura_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':factura_id', $factura_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    // Contar productos de una factura
    public function countByFactura($factura_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE factura_id = :factura_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':factura_id', $factura_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}
?>