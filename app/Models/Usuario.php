<?php
namespace App\Models;

use Config\Database;
use PDO;

class Usuario {

    private $pdo;
    private $table = 'usuarios_sistema';

    public function __construct() {
        // ✔ YA ES PDO
        $this->pdo = Database::getInstance();
    }

    /* =========================
       MÉTODOS QUE YA TENÍAS
    ========================== */

    public function getAllExcept($currentId) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id != :id AND deleted_at IS NULL 
                ORDER BY id DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $currentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function exists($correo) {
        $sql = "SELECT id FROM {$this->table} WHERE correo = :cor LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo]);
        return $stmt->fetch();
    }

    public function getByCorreo($correo) {
        $sql = "SELECT * FROM {$this->table} WHERE correo = :cor AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cor' => $correo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function softDelete($id) {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE deleted_at IS NULL";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }

    /* =========================
       🔥 MÉTODOS NUEVOS (PDO)
    ========================== */

    // ✔ Buscar por ID (Perfil)
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id = :id AND deleted_at IS NULL";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✔ Actualizar Perfil (con o sin password)
    public function updatePerfil($id, $nombres, $apellidos, $password = null) {

        if ($password) {
            $sql = "UPDATE {$this->table}
                    SET nombres = :nom, apellidos = :ape, password = :pass
                    WHERE id = :id";

            $params = [
                ':nom'  => $nombres,
                ':ape'  => $apellidos,
                ':pass' => password_hash($password, PASSWORD_BCRYPT),
                ':id'   => $id
            ];
        } else {
            $sql = "UPDATE {$this->table}
                    SET nombres = :nom, apellidos = :ape
                    WHERE id = :id";

            $params = [
                ':nom' => $nombres,
                ':ape' => $apellidos,
                ':id'  => $id
            ];
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}
