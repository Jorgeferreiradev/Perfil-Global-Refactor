<?php
namespace App\Models;

use Config\Database;
use PDO;

class Periodo {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    public function getActivoId() {
        // CORRECCIÓN: Buscamos por la columna 'estado' con valor 'activo'
        $stmt = $this->pdo->prepare("SELECT id FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
        $stmt->execute();
        return $stmt->fetchColumn(); // Devuelve el ID (ej: 1) o false
    }
}