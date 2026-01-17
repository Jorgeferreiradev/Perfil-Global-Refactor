<?php
namespace App\Models;

use Config\Database;
use PDO;

class Reporte {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Cuenta cuántos eventos ya terminaron basándose en fecha y hora final.
     * Esto alimenta el contador de "Reportes Generados" en el Dashboard.
     */
    public function contarFinalizados() {
        try {
            // Un evento genera un reporte cuando la fecha/hora actual es superior a su finalización
            $sql = "SELECT COUNT(*) as total 
                    FROM eventos 
                    WHERE CONCAT(fecha_final, ' ', hora_final) < NOW()";
            
            $stmt = $this->db->query($sql);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado['total'] ?? 0;
        } catch (\PDOException $e) {
            error_log("Error en Reporte::contarFinalizados: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Senior Tip: Métrica de Beneficiarios Reales
     * Este método será vital para tu Sprint 2 y 3.
     * Cuenta personas únicas (sin repetir cédula) en un rango de tiempo.
     */
    public function obtenerBeneficiariosReales($filtros = []) {
        // Esta lógica la desarrollaremos a fondo en el Sprint de Reportes
        // Pero aquí ya tienes la base: COUNT(DISTINCT ...)
        $sql = "SELECT COUNT(DISTINCT persona_id) as total FROM asistencias";
        // ... lógica de filtros por año/semestre/linea ...
    }
}