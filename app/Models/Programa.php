<?php
namespace App\Models;

use Config\Database;
use PDO;

class Programa {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getAll() {
        // Excluimos el ID 99 porque es el "Invitado/Externo" del sistema
        $sql = "SELECT * FROM programas WHERE id_programa != 99 ORDER BY nombre_programa ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM programas WHERE id_programa = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nombre) {
        $sql = "INSERT INTO programas (nombre_programa, estado) VALUES (:nombre, 'activo')";
        return $this->pdo->prepare($sql)->execute([':nombre' => strtoupper(trim($nombre))]);
    }

    public function update($id, $nombre) {
        $sql = "UPDATE programas SET nombre_programa = :nombre WHERE id_programa = :id";
        return $this->pdo->prepare($sql)->execute([
            ':nombre' => strtoupper(trim($nombre)),
            ':id' => $id
        ]);
    }

    // 🔥 LA "D" DEL CRUD: Alternar entre activo e inactivo
    public function toggle($id) {
        $sql = "UPDATE programas SET estado = IF(estado = 'activo', 'inactivo', 'activo') WHERE id_programa = :id";
        return $this->pdo->prepare($sql)->execute([':id' => $id]);
    }
}