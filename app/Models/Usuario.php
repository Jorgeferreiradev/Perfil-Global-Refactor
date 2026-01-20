<?php
namespace App\Models;

use Config\Database;
use PDO;

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Para el Login
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios_sistema WHERE correo = :correo AND deleted_at IS NULL LIMIT 1");
        $stmt->bindParam(':correo', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Para listar en la tabla (Excluye al usuario actual para no auto-borrarse)
    public function getAllExcept($currentUserId) {
        $sql = "SELECT * FROM usuarios_sistema 
                WHERE deleted_at IS NULL AND id != :id 
                ORDER BY rol ASC, apellidos ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $currentUserId]);
        return $stmt->fetchAll();
    }

    // Para verificar duplicados antes de crear
    public function exists($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios_sistema WHERE correo = :correo");
        $stmt->execute([':correo' => $email]);
        return $stmt->fetchColumn();
    }

    // Para crear nuevo usuario
    public function create($data) {
        $sql = "INSERT INTO usuarios_sistema (nombres, apellidos, correo, password, rol) 
                VALUES (:nom, :ape, :cor, :pass, :rol)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nom'  => $data['nombres'],
            ':ape'  => $data['apellidos'],
            ':cor'  => $data['correo'],
            ':pass' => $data['password'], // Ya debe venir hasheada
            ':rol'  => $data['rol']
        ]);
    }

    // Para borrar (Soft Delete)
    public function softDelete($id) {
        $sql = "UPDATE usuarios_sistema SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}