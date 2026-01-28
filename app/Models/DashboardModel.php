<?php

namespace App\Models;

use Config\Database;
use PDO;

class DashboardModel
{
    private $db; // PDO

    public function __construct()
    {
        // Igual que PersonaModel ✅
        $this->db = Database::getInstance();
    }

    // 1. Total Personas (Activas)
    public function getTotalPersonas()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM personas 
                WHERE estado_aprobacion = 'activo' 
                AND deleted_at IS NULL";

        return $this->db
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 1.1 y 1.2 Totales por Tipo
    public function getConteoPorTipos()
    {
        $sql = "SELECT tp.nombre_tipo, COUNT(p.id) as cantidad
                FROM personas p
                JOIN tipos_personas tp ON p.id_tipo_persona = tp.id_tipo
                WHERE p.estado_aprobacion = 'activo'
                AND p.deleted_at IS NULL
                GROUP BY tp.nombre_tipo";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        // ['Estudiante' => 50, 'Docente' => 10, ...]
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    // 2. Total Eventos
    public function getTotalEventos()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM eventos 
                WHERE deleted_at IS NULL";

        return $this->db
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 3.1 Usuarios del Sistema
    public function getTotalUsuariosSistema()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM usuarios_sistema 
                WHERE deleted_at IS NULL";

        return $this->db
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 3.2 Cargas Masivas Exitosas
    public function getTotalCargasMasivas()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM logs_sistema 
                WHERE accion LIKE '%exitos%' 
                OR accion LIKE '%correctamente%'";

        return $this->db
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // 3.3 Pendientes de Aprobación
    public function getPendientes()
    {
        $sql = "SELECT COUNT(*) as total 
                FROM personas 
                WHERE estado_aprobacion = 'pendiente' 
                AND deleted_at IS NULL";

        return $this->db
            ->query($sql)
            ->fetch(PDO::FETCH_ASSOC)['total'];
    }
}