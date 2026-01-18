<?php
namespace App\Models;

use Config\Database;
use PDO;

class Reporte {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    /**
     * Obtiene la data cruda para la Matriz Semestral.
     * Filtra automáticamente datos de simulación si no es DEV.
     */
    public function getMatrizSemestral($fechaInicio, $fechaFin, $programaId = null) {
        $sql = "SELECT 
                    p.documento,
                    CONCAT(p.nombres, ' ', p.apellidos) as estudiante,
                    pr.nombre_programa,
                    e.nombre_evento,
                    l.nombre_linea as linea_accion,
                    e.fecha_inicio,
                    e.sede,
                    a.fecha_asistencia
                FROM asistencias a
                INNER JOIN eventos e ON a.id_evento = e.id_evento
                INNER JOIN personas p ON a.persona_id = p.id
                INNER JOIN historial_academico ha ON p.id = ha.persona_id 
                    AND ha.periodo_id = (SELECT id FROM periodos_academicos WHERE activo = 1 LIMIT 1)
                INNER JOIN programas pr ON ha.id_programa = pr.id_programa
                INNER JOIN lineas_accion l ON e.id_linea_accion = l.id
                WHERE e.fecha_inicio BETWEEN :inicio AND :fin
                AND e.deleted_at IS NULL";

        // HU-D02: Si NO es modo sandbox, excluir simulaciones
        if (!isset($_SESSION['is_sandbox'])) {
            $sql .= " AND e.es_simulacion = 0";
        }

        // Filtro opcional por programa
        if ($programaId) {
            $sql .= " AND pr.id_programa = :programa";
        }

        $sql .= " ORDER BY a.fecha_asistencia DESC";

        $stmt = $this->pdo->prepare($sql);
        
        $params = [
            ':inicio' => $fechaInicio,
            ':fin'    => $fechaFin
        ];

        if ($programaId) {
            $params[':programa'] = $programaId;
        }

        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Datos para Dashboard (KPIs rápidos)
     */
    public function getKpis($anio, $semestre) {
        // Total Asistencias Reales
        $sql = "SELECT COUNT(*) as total FROM asistencias a 
                JOIN eventos e ON a.id_evento = e.id_evento 
                WHERE e.ano = :ano AND e.semestre = :sem AND e.es_simulacion = 0";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':ano' => $anio, ':sem' => $semestre]);
        $total = $stmt->fetchColumn();

        return ['total_asistencias' => $total];
    }
}