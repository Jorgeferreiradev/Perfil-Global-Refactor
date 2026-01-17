<?php
namespace App\Models;

use Config\Database;

class Log {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Registra una acción en la auditoría del sistema
     */
    public function registrar($usuario_id, $accion) {
        $sql = "INSERT INTO logs_sistema (usuario_id, accion) VALUES (:u_id, :acc)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':u_id' => $usuario_id,
            ':acc'  => $accion
        ]);
    }
}