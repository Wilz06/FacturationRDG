<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $conn;
    private $table = 'clientes';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    // Obtener todos los clientes
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Obtener cliente por ID
    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Crear nuevo cliente
    public function create($data) {
        $query = "INSERT INTO {$this->table} (nombre, documento, telefono, email, direccion) 
                  VALUES (:nombre, :documento, :telefono, :email, :direccion)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':documento', $data['documento']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':direccion', $data['direccion']);
        
        return $stmt->execute();
    }
    
    // Buscar cliente por nombre
    public function buscarPorNombre($nombre) {
        $query = "SELECT * FROM {$this->table} WHERE nombre LIKE :nombre LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$nombre}%";
        $stmt->bindParam(':nombre', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>