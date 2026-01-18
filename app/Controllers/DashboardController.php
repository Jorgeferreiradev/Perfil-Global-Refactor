<?php
namespace App\Controllers;

class DashboardController {
    
    public function index() {
        // 1. Verificar sesión (Doble check de seguridad)
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $rol = $_SESSION['user_rol'] ?? 'monitor';

        // Variables para la vista
        $title = "Dashboard - " . ucfirst($rol);
        $active = 'dashboard';

        // 2. Cargar las Vistas (Rutas corregidas apuntando a resources)
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';

        // 3. Cargar el cuerpo según el rol
        if ($rol === 'admin' || $rol === 'dev') {
            require_once __DIR__ . '/../../resources/views/dashboard/admin.php';
        } else {
            require_once __DIR__ . '/../../resources/views/dashboard/monitor.php';
        }

        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }
}