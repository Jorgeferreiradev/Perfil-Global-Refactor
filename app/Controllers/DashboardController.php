<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Usuario;

class DashboardController {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Verificación de seguridad (Middleware manual)
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $rol = $_SESSION['user_rol'];
        $userId = $_SESSION['user_id'];
        $data = [];

        // --- LÓGICA RBAC DE DATOS ---
        
        // 1. Datos para el MONITOR (y Admin también los ve, pero globales)
        // Aquí conectarías con tus modelos reales para contar eventos
        // $eventoModel = new Evento();
        // $data['mis_eventos'] = $eventoModel->contarPorUsuario($userId);
        
        // Datos Mock (Simulados para que veas el diseño ya)
        $data['eventos_activos'] = 5; 
        $data['asistencias_hoy'] = 120;

        // 2. Datos Exclusivos para ADMIN
        if ($rol === 'admin') {
            // $usrModel = new Usuario();
            // $data['total_usuarios'] = $usrModel->contarTodos();
            $data['total_usuarios'] = 45; // Simulado
            $data['total_carreras'] = 8;
        }

        $title = "Dashboard - PerfilGlobal";
        $active = 'dashboard';

        // Renderizar la Vista Única
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/index.php'; // <--- ÚNICO ARCHIVO
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }
}