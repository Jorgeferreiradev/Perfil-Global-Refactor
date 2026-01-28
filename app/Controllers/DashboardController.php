<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Asistencia;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\DashboardModel;
use Config\Database;

class DashboardController {

    public function index() {

        // 🔐 Verificar sesión
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Inicializar modelos
        $eventoModel    = new Evento();
        $personaModel   = new Persona();
        $dashboardModel = new DashboardModel();

        // ─────────────────────────────
        // 0️⃣ Semestre y Año actual
        // ─────────────────────────────
        $mes  = date('n');
        $anio = date('Y');
        $semestreLabel = ($mes <= 6) ? "I (Ene - Jun)" : "II (Jul - Dic)";

        // ─────────────────────────────
        // 1️⃣ KPIs principales
        // ─────────────────────────────
        $hoy = date('Y-m-d');
        $asistenciasHoy = $this->getConteoAsistenciasHoy($hoy);

        $eventosActivos = $eventoModel->all();
        $totalEventos   = $dashboardModel->getTotalEventos();

        // ─────────────────────────────
        // 2️⃣ Personas por tipo
        // ─────────────────────────────
        $tipos = $dashboardModel->getConteoPorTipos();

        // ─────────────────────────────
        // 3️⃣ Pendientes y usuarios
        // ─────────────────────────────
        $totalPendientes = $personaModel->contarPendientes();
        $_SESSION['pendientes_count'] = $totalPendientes;

        // ─────────────────────────────
        // 4️⃣ DATA FINAL PARA LA VISTA
        // ─────────────────────────────
        $data = [

            // Semestre
            'semestre_actual' => $semestreLabel,
            'anio_actual'     => $anio,

            // KPIs superiores
            'asistencias_hoy' => $asistenciasHoy,
            'total_eventos'   => $totalEventos,
            'eventos_activos' => $eventosActivos,

            // Personas
            'total_personas'        => $dashboardModel->getTotalPersonas(),
            'total_estudiantes'     => $tipos['Estudiante'] ?? 0,
            'total_docentes'        => $tipos['Docente'] ?? 0,
            'total_administrativos' => $tipos['Administrativo'] ?? 0,
            'total_egresados'       => $tipos['Egresado'] ?? 0,
            'total_invitados'       => ($tipos['Invitado'] ?? 0) + ($tipos['Externo'] ?? 0),

            // Admin
            'total_pendientes'       => $totalPendientes,
            'total_usuarios_sistema' => (new Usuario())->countAll(),
            'total_cargas'           => $dashboardModel->getTotalCargasMasivas(),
        ];

        // Layout + vista
        $title  = "Panel de Control";
        $active = "dashboard";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    // 🔧 Helper para asistencias de hoy
    private function getConteoAsistenciasHoy($fecha) {
        $db = Database::getInstance();

        $sql = "SELECT COUNT(*) AS total 
                FROM asistencias 
                WHERE DATE(fecha_asistencia) = ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$fecha]);
        $res = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $res ? $res['total'] : 0;
    }
}
