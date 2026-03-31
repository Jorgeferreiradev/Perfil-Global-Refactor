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
     * 1. OBTENER LÍNEAS DE ACCIÓN DEL SEMESTRE
     * 🔥 AHORA RECIBE LA SEDE
     */
    public function getLineasConEventos($fechaInicio, $fechaFin, $sede = 'Todas') {
        $sql = "SELECT DISTINCT l.nombre_linea
                FROM eventos e
                JOIN lineas_accion l ON e.id_linea_accion = l.id
                WHERE (e.fecha_inicio BETWEEN :fi AND :ff)
                AND e.estado = 'activo'";
        
        $params = [':fi' => $fechaInicio, ':ff' => $fechaFin];

        // Lógica Senior: Construcción dinámica de SQL
        if ($sede !== 'Todas') {
            $sql .= " AND e.sede = :sede";
            $params[':sede'] = $sede;
        }

        $sql .= " ORDER BY l.nombre_linea";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * 2. DATOS DE EVENTOS POR LÍNEA
     * 🔥 AHORA RECIBE LA SEDE
     */
    public function getEventosPorLinea($nombreLinea, $fechaInicio, $fechaFin, $sede = 'Todas') {
        $sql = "SELECT 
                    e.id_evento, e.nombre_evento, e.fecha_inicio, e.fecha_final,
                    COUNT(CASE WHEN p.id_tipo_persona = 1 THEN 1 END) as est,
                    COUNT(CASE WHEN p.id_tipo_persona = 2 THEN 1 END) as doc,
                    COUNT(CASE WHEN p.id_tipo_persona = 3 THEN 1 END) as adm,
                    COUNT(CASE WHEN p.id_tipo_persona = 4 THEN 1 END) as grad,
                    COUNT(CASE WHEN p.id_tipo_persona = 5 THEN 1 END) as ext, 
                    COUNT(a.id) as total_bruto
                FROM eventos e
                JOIN lineas_accion l ON e.id_linea_accion = l.id
                LEFT JOIN asistencias a ON e.id_evento = a.id_evento
                LEFT JOIN personas p ON a.persona_id = p.id
                WHERE l.nombre_linea = :linea 
                AND (e.fecha_inicio BETWEEN :fi AND :ff)
                AND e.estado = 'activo'";
        
        $params = [':linea' => $nombreLinea, ':fi' => $fechaInicio, ':ff' => $fechaFin];

        if ($sede !== 'Todas') {
            $sql .= " AND e.sede = :sede";
            $params[':sede'] = $sede;
        }

        $sql .= " GROUP BY e.id_evento";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3. BENEFICIARIOS REALES (Sin duplicados)
     * 🔥 AHORA RECIBE LA SEDE
     */
    public function getBeneficiariosRealesPorLinea($nombreLinea, $fechaInicio, $fechaFin, $sede = 'Todas') {
        $sql = "SELECT 
                    COUNT(DISTINCT CASE WHEN p.id_tipo_persona = 1 THEN p.numero_documento END) as real_est,
                    COUNT(DISTINCT CASE WHEN p.id_tipo_persona = 2 THEN p.numero_documento END) as real_doc,
                    COUNT(DISTINCT CASE WHEN p.id_tipo_persona = 3 THEN p.numero_documento END) as real_adm,
                    COUNT(DISTINCT CASE WHEN p.id_tipo_persona = 4 THEN p.numero_documento END) as real_grad,
                    COUNT(DISTINCT CASE WHEN p.id_tipo_persona = 5 THEN p.numero_documento END) as real_ext
                FROM eventos e
                JOIN lineas_accion l ON e.id_linea_accion = l.id
                JOIN asistencias a ON e.id_evento = a.id_evento
                JOIN personas p ON a.persona_id = p.id
                WHERE l.nombre_linea = :linea 
                AND (e.fecha_inicio BETWEEN :fi AND :ff)
                AND e.estado = 'activo'"; 

        $params = [':linea' => $nombreLinea, ':fi' => $fechaInicio, ':ff' => $fechaFin];

        if ($sede !== 'Todas') {
            $sql .= " AND e.sede = :sede";
            $params[':sede'] = $sede;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 4. DETALLE DE ASISTENTES POR EVENTO
     * (Este no necesita filtro de sede porque ya está atado al ID de un evento en particular)
     */
/**
     * 4. DETALLE DE ASISTENTES POR EVENTO
     *  Unificado para coincidir 100% con la Matriz
     */
    public function getDetalleAsistentes($idEvento) {
        $sql = "SELECT 
                    p.numero_documento, 
                    CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo,
                    IFNULL(t.nombre_tipo, 'Invitado') as tipo_vinculacion,
                    IFNULL((
                        SELECT pr.nombre_programa
                        FROM programas pr
                        INNER JOIN historial_academico ha ON ha.id_programa = pr.id_programa
                        WHERE ha.persona_id = p.id
                        ORDER BY ha.id DESC LIMIT 1
                    ), 'Sin Programa / No Aplica') as programa_dependencia,
                    a.fecha_asistencia
                FROM asistencias a
                JOIN personas p ON a.persona_id = p.id
                LEFT JOIN tipos_personas t ON p.id_tipo_persona = t.id_tipo
                WHERE a.id_evento = :id
                ORDER BY t.id_tipo ASC, p.apellidos ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idEvento]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 5. INFO EVENTO
     */
    public function getInfoEvento($id) {
        $sql = "SELECT e.*, l.nombre_linea as linea_accion 
                FROM eventos e
                LEFT JOIN lineas_accion l ON e.id_linea_accion = l.id
                WHERE e.id_evento = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}