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
     * Hacemos JOIN con la tabla 'lineas_accion' para traer el nombre real
     */
    public function getLineasConEventos($fechaInicio, $fechaFin) {
        $sql = "SELECT DISTINCT l.nombre_linea
                FROM eventos e
                JOIN lineas_accion l ON e.id_linea_accion = l.id
                WHERE (e.fecha_inicio BETWEEN :fi AND :ff)
                ORDER BY l.nombre_linea";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':fi' => $fechaInicio, ':ff' => $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * 2. DATOS DE EVENTOS POR LÍNEA
     * Filtramos por el nombre de la línea obtenido en el paso 1
     */
    public function getEventosPorLinea($nombreLinea, $fechaInicio, $fechaFin) {
        // Nota: Ajusté el ID 5 para Externos/Invitados según tu BD
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
                GROUP BY e.id_evento";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':linea' => $nombreLinea, ':fi' => $fechaInicio, ':ff' => $fechaFin]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3. BENEFICIARIOS REALES (Sin duplicados)
     * Usamos JOIN para filtrar por la línea correcta
     */
    public function getBeneficiariosRealesPorLinea($nombreLinea, $fechaInicio, $fechaFin) {
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
                AND (e.fecha_inicio BETWEEN :fi AND :ff)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':linea' => $nombreLinea, ':fi' => $fechaInicio, ':ff' => $fechaFin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 4. DETALLE DE ASISTENTES
     * Usa tus tablas reales: programas, tipos_personas
     */
    public function getDetalleAsistentes($idEvento) {
        $sql = "SELECT 
                    p.numero_documento, 
                    CONCAT(p.nombres, ' ', p.apellidos) as nombre_completo,
                    t.nombre_tipo as tipo_vinculacion,
                    IFNULL(prog.nombre_programa, 'Sin Programa') as programa_dependencia,
                    a.fecha_asistencia
                FROM asistencias a
                JOIN personas p ON a.persona_id = p.id
                JOIN historial_academico h ON p.id = h.persona_id
                JOIN tipos_personas t ON h.id_tipo_persona = t.id_tipo
                LEFT JOIN programas prog ON h.id_programa = prog.id_programa
                WHERE a.id_evento = :id
                ORDER BY t.id_tipo ASC, p.apellidos ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $idEvento]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * 5. INFO EVENTO
     * Hacemos JOIN para obtener 'linea_accion' como nombre, 
     * así el controlador no falla al buscar esa clave.
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