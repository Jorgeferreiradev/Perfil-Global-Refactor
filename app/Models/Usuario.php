<?php
namespace App\Models;

use Config\Database;
use PDO;

class Usuario {
    private $pdo;
    
    // [BUENA PRÁCTICA] Definimos la tabla aquí para no equivocarnos luego
    private $table = 'usuarios_sistema'; 

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // 1. Obtener todos (excepto el actual) - Para Admin
    public function getAllExcept($currentId) {
        // Usamos $this->table para referirnos a 'usuarios_sistema'
        $sql = "SELECT * FROM {$this->table} 
                WHERE id != :id AND deleted_at IS NULL 
                ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $currentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Crear Usuario
    public function create($data) {
        $sql = "INSERT INTO {$this->table} (nombres, apellidos, correo, password, rol) 
                VALUES (:nom, :ape, :cor, :pass, :rol)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':cor'  => $data['correo'],
            ':pass' => $data['password'],
            ':rol'  => $data['rol']
        ]);
    }

    // 3. Verificar si existe email (para evitar duplicados)
    public function exists($correo) {
        $sql = "SELECT id FROM {$this->table} WHERE correo = :cor LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo]);
        return $stmt->fetch();
    }

    // 4. Login (Buscar por correo)
    public function getByCorreo($correo) {
        $sql = "SELECT * FROM {$this->table} WHERE correo = :cor AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Soft Delete (Borrado lógico)
    public function softDelete($id) {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // 6. Contar Total (Para el Dashboard) - [AQUÍ ESTABA EL ERROR]
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE deleted_at IS NULL";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }
}