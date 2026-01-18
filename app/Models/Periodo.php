<?php
namespace App\Models;
use Config\Database;
use PDO;

class Periodo {
    private $pdo;
    public function __construct() { $this->pdo = Database::getInstance(); }

    public function getActivoId() {
        $stmt = $this->pdo->query("SELECT id FROM periodos_academicos WHERE activo = 1 LIMIT 1");
        return $stmt->fetchColumn(); 
    }
}