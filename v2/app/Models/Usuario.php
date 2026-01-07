<?php
namespace App\Models;

use Config\Database;
use PDO;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function buscarPorEmail($correo) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios_sistema WHERE correo = :correo LIMIT 1");
        $stmt->execute(['correo' => $correo]);
        return $stmt->fetch();
    }
}