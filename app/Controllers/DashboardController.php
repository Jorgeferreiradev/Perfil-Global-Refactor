<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Asistencia;
use App\Models\Persona;
use App\Models\Usuario;
use Config\Database; // Necesario para la conexión directa en el helper

class DashboardController {

    public function index() {
        // Inicializamos modelos
        $eventoModel = new Evento();
        $personaModel = new Persona();
        // $usuarioModel se instancia abajo directo

        // --- DATOS REALES (KPIs) ---
        
        // 1. Asistencias HOY (Global)
        $hoy = date('Y-m-d');
        // Usamos el helper corregido con 'fecha_asistencia'
        $asistenciasHoy = $this->getConteoAsistenciasHoy($hoy); 

        // 2. Eventos Activos (Para listar en el dashboard)
        // Buscamos eventos que estén ocurriendo hoy
        $eventosActivos = $eventoModel->all(); 
        // (Si quisieras filtrar solo los de hoy, harías un filtro aquí, 
        // pero mejor mostrar todos para que se vea lleno el dashboard)

        // 3. Pendientes (Para el globo rojo y la tarjeta de alerta)
        $totalPendientes = $personaModel->contarPendientes();
        $_SESSION['pendientes_count'] = $totalPendientes; 

        // 4. Preparar DATA para la vista
        $data = [
            'asistencias_hoy' => $asistenciasHoy,
            'eventos_activos' => $eventosActivos, // Pasamos la lista real
            'total_pendientes'=> $totalPendientes,
            'total_usuarios'  => (new Usuario())->countAll()
        ];

        $title = "Panel de Control";
        $active = "dashboard";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    //Helper privado para conteo rápido
    private function getConteoAsistenciasHoy($fecha) {
        $db = Database::getInstance();
        
        // [CORRECCIÓN AQUÍ] Usamos 'fecha_asistencia' que es tu columna real
        $sql = "SELECT COUNT(*) as total FROM asistencias WHERE DATE(fecha_asistencia) = ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$fecha]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return $res ? $res['total'] : 0;
    }
}