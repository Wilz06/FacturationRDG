<?php
require_once __DIR__ . '/../config/database.php';

class Factura {
    private $conn;
    private $table = 'facturas';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        // Obtenemos los datos con las relaciones
        $query = "SELECT f.*, 
                  e.nombre as empresa_nombre,
                  c.nombre as cliente_nombre
                  FROM {$this->table} f
                  LEFT JOIN empresas e ON f.empresa_id = e.id
                  LEFT JOIN clientes c ON f.cliente_id = c.id
                  ORDER BY f.fecha_emision DESC, f.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (empresa_id, cliente_id, laboratorio, cliente, numero_factura, ruc, direccion, telefono, email, 
                   fecha_emision, fecha_vencimiento, tipo_pago, moneda, subtotal, igv, 
                   monto_total, estado, observaciones) 
                  VALUES 
                  (:empresa_id, :cliente_id, :laboratorio, :cliente, :numero_factura, :ruc, :direccion, :telefono, :email,
                   :fecha_emision, :fecha_vencimiento, :tipo_pago, :moneda, :subtotal, :igv,
                   :monto_total, :estado, :observaciones)";
        
        $stmt = $this->conn->prepare($query);
        
        // IDs Nuevos
        $stmt->bindParam(':empresa_id', $data['empresa_id'], PDO::PARAM_INT);
        $stmt->bindParam(':cliente_id', $data['cliente_id'], PDO::PARAM_INT);
        
        // Datos (los mantenemos por compatibilidad)
        $stmt->bindParam(':laboratorio', $data['laboratorio']);
        $stmt->bindParam(':cliente', $data['cliente']);
        
        // Resto de los campos
        $stmt->bindParam(':numero_factura', $data['numero_factura']);
        $stmt->bindParam(':ruc', $data['ruc']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':fecha_emision', $data['fecha_emision']);
        $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
        $stmt->bindParam(':tipo_pago', $data['tipo_pago']);
        $stmt->bindParam(':moneda', $data['moneda']);
        $stmt->bindParam(':subtotal', $data['subtotal']);
        $stmt->bindParam(':igv', $data['igv']);
        $stmt->bindParam(':monto_total', $data['monto_total']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':observaciones', $data['observaciones']);
        
        return $stmt->execute();
    }
    
    public function update($id, $data) {
        $query = "UPDATE {$this->table} SET 
                    empresa_id = :empresa_id,
                    cliente_id = :cliente_id,
                    laboratorio = :laboratorio,
                    cliente = :cliente,
                    numero_factura = :numero_factura,
                    ruc = :ruc,
                    direccion = :direccion,
                    telefono = :telefono,
                    email = :email,
                    fecha_emision = :fecha_emision,
                    fecha_vencimiento = :fecha_vencimiento,
                    tipo_pago = :tipo_pago,
                    moneda = :moneda,
                    subtotal = :subtotal,
                    igv = :igv,
                    monto_total = :monto_total,
                    estado = :estado,
                    observaciones = :observaciones
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // IDs Nuevos
        $stmt->bindParam(':empresa_id', $data['empresa_id'], PDO::PARAM_INT);
        $stmt->bindParam(':cliente_id', $data['cliente_id'], PDO::PARAM_INT);
        
        // Resto de los campos
        $stmt->bindParam(':laboratorio', $data['laboratorio']);
        $stmt->bindParam(':cliente', $data['cliente']);
        $stmt->bindParam(':numero_factura', $data['numero_factura']);
        $stmt->bindParam(':ruc', $data['ruc']);
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':fecha_emision', $data['fecha_emision']);
        $stmt->bindParam(':fecha_vencimiento', $data['fecha_vencimiento']);
        $stmt->bindParam(':tipo_pago', $data['tipo_pago']);
        $stmt->bindParam(':moneda', $data['moneda']);
        $stmt->bindParam(':subtotal', $data['subtotal']);
        $stmt->bindParam(':igv', $data['igv']);
        $stmt->bindParam(':monto_total', $data['monto_total']);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':observaciones', $data['observaciones']);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function existeNumeroFactura($numero_factura, $exclude_id = null) {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE numero_factura = :numero_factura";
        
        if ($exclude_id) {
            $query .= " AND id != :exclude_id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':numero_factura', $numero_factura);
        
        if ($exclude_id) {
            $stmt->bindParam(':exclude_id', $exclude_id, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result['count'] > 0;
    }

    // Obtener todas las facturas con empresa y cliente
    public function getAllConRelaciones() {
        $query = "SELECT f.*, 
                  e.nombre as empresa_nombre,
                  c.nombre as cliente_nombre,
                  c.documento as cliente_documento
                  FROM {$this->table} f
                  LEFT JOIN empresas e ON f.empresa_id = e.id
                  LEFT JOIN clientes c ON f.cliente_id = c.id
                  ORDER BY f.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Filtrar facturas
    public function filtrar($empresa_id = null, $cliente_id = null, $fecha_desde = null, $fecha_hasta = null, $estado = null) {
        $query = "SELECT f.*, 
                  e.nombre as empresa_nombre,
                  c.nombre as cliente_nombre
                  FROM {$this->table} f
                  LEFT JOIN empresas e ON f.empresa_id = e.id
                  LEFT JOIN clientes c ON f.cliente_id = c.id
                  WHERE 1=1";
        
        $params = [];
        
        if ($empresa_id) {
            $query .= " AND f.empresa_id = :empresa_id";
            $params[':empresa_id'] = $empresa_id;
        }
        
        if ($cliente_id) {
            $query .= " AND f.cliente_id = :cliente_id";
            $params[':cliente_id'] = $cliente_id;
        }
        
        if ($fecha_desde) {
            $query .= " AND f.fecha_emision >= :fecha_desde";
            $params[':fecha_desde'] = $fecha_desde;
        }
        
        if ($fecha_hasta) {
            $query .= " AND f.fecha_emision <= :fecha_hasta";
            $params[':fecha_hasta'] = $fecha_hasta;
        }
        
        if ($estado) {
            $query .= " AND f.estado = :estado";
            $params[':estado'] = $estado;
        }
        
        $query .= " ORDER BY f.id DESC";
        
        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Obtener el último ID insertado
    public function getUltimoId() {
        return $this->conn->lastInsertId();
    }
}
?>