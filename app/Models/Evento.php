<?php
namespace App\Models;
use Config\Database;
use PDO;

class Evento {
    private $db;
    public function __construct() { $this->db = Database::getInstance(); }

    public function contarProximos() {
    // AJUSTA 'fecha' al nombre real de tu columna en la DB
    $sql = "SELECT COUNT(*) as total FROM eventos WHERE fecha_evento >= CURDATE()";
    
    // Tip Senior: Usa try-catch para que un error de DB no rompa toda la app
    try {
        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    } catch (\PDOException $e) {
        // Loguear el error para auditoría
        error_log("Error en contarProximos: " . $e->getMessage());
        return 0;
    }
}
}