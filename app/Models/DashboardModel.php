<?php

namespace App\Models;

use Config\Database;
use PDO;

class DashboardModel
{
    private $db; // PDO

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // 🔥 SENIOR FIX: Helper centralizado para no repetir la llamada a la sesión
    // Retorna 0 si no hay sesión, para evitar errores fatales de SQL
    private function getPeriodoActivo() {
        return $_SESSION['periodo_vista_id'] ?? 0;
    }

    // 1. Total Personas (Activas EN EL SEMESTRE)
    public function getTotalPersonas()
    {
        $periodo = $this->getPeriodoActivo();
        $sql = "SELECT COUNT(DISTINCT p.id) as total 
                FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                WHERE p.estado_aprobacion = 'activo' 
                AND p.deleted_at IS NULL
                AND ha.periodo_id = :periodo";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':periodo' => $periodo]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 1.1 y 1.2 Totales por Tipo (EN EL SEMESTRE)
    public function getConteoPorTipos()
    {
        $periodo = $this->getPeriodoActivo();
        $sql = "SELECT tp.nombre_tipo, COUNT(DISTINCT p.id) as cantidad
                FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                JOIN tipos_personas tp ON p.id_tipo_persona = tp.id_tipo
                WHERE p.estado_aprobacion = 'activo'
                AND p.deleted_at IS NULL
                AND ha.periodo_id = :periodo
                GROUP BY tp.nombre_tipo";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':periodo' => $periodo]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    // 2. Total Eventos (EN EL SEMESTRE)
    public function getTotalEventos()
    {
        $periodo = $this->getPeriodoActivo();
        $sql = "SELECT COUNT(*) as total 
                FROM eventos 
                WHERE deleted_at IS NULL
                AND id_periodo = :periodo";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':periodo' => $periodo]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 3.1 Usuarios del Sistema (Este lo dejamos GLOBAL, los admins no dependen de un semestre)
    public function getTotalUsuariosSistema()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM usuarios_sistema 
                WHERE deleted_at IS NULL";

        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC)['total'];
    }

// 3.2 Cargas Masivas Exitosas (EN EL SEMESTRE)
    public function getTotalCargasMasivas()
    {
        $periodo = $this->getPeriodoActivo();
        
        // Opción A: Si tu tabla logs_sistema tiene una columna 'id_periodo'
        /*
        $sql = "SELECT COUNT(*) as total 
                FROM logs_sistema 
                WHERE (accion LIKE '%exitos%' OR accion LIKE '%correctamente%')
                AND id_periodo = :periodo";
        */

        // Opción B (La más segura): Cruzamos la fecha del log con las fechas del semestre actual
        $sql = "SELECT COUNT(*) as total 
                FROM logs_sistema ls
                INNER JOIN periodos_academicos pa ON pa.id = :periodo
                WHERE (ls.accion LIKE '%exitos%' OR ls.accion LIKE '%correctamente%')
                AND DATE(ls.fecha) BETWEEN pa.fecha_inicio AND pa.fecha_fin";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':periodo' => $periodo]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 3.3 Pendientes de Aprobación (EN EL SEMESTRE)
    public function getPendientes()
    {
        $periodo = $this->getPeriodoActivo();
        $sql = "SELECT COUNT(DISTINCT p.id) as total 
                FROM personas p
                INNER JOIN historial_academico ha ON p.id = ha.persona_id
                WHERE p.estado_aprobacion = 'pendiente' 
                AND p.deleted_at IS NULL
                AND ha.periodo_id = :periodo";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':periodo' => $periodo]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}