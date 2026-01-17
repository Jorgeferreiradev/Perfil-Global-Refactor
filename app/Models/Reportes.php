<?php
namespace App\Models;
use Config\Database;
use PDO;

class Certificado {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function contarTotal() {
        $sql = "SELECT COUNT(*) as total FROM certificados";
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }
}