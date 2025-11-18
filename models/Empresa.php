<?php
require_once __DIR__ . '/../config/database.php';

class Empresa {
    private $conn;
    private $table = 'empresas';
    
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY nombre ASC";
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
        $query = "INSERT INTO {$this->table} (nombre, documento, tipo_documento) 
                  VALUES (:nombre, :documento, :tipo_documento)";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':documento', $data['documento']);
        $stmt->bindParam(':tipo_documento', $data['tipo_documento']);
        
        return $stmt->execute();
    }

    public function getUltimoId() {
        return $this->conn->lastInsertId();
    }
}
?>