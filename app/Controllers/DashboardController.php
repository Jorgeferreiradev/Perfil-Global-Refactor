<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Evento;
use App\Models\Asistencia;

class DashboardController {

    public function index() {
        // 1. Verificación de Seguridad
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $_ENV['APP_URL'] . '/login');
            exit;
        }

        // 2. Instanciar Modelos para obtener datos reales
        $userModel = new Usuario();
        $eventoModel = new Evento();
        $asistenciaModel = new Asistencia();

        $rol = $_SESSION['rol'] ?? 'monitor';

        // 3. Obtención de conteos reales (Asegúrate de tener estos métodos en tus modelos)
        $data = [
            'titulo'            => 'Panel de Control | PG V2',
            'nombre'            => $_SESSION['user_name'] ?? 'Usuario',
            'rol'               => $rol,
            'active'            => 'inicio',
            'totalUsuarios'     => $userModel->countAll(), // CU-C02: Total personas en BD
            'totalEventos'      => $eventoModel->countAll(), // Total eventos creados
            'totalCertificados' => $asistenciaModel->countAll() // Total asistencias (certificados)
        ];

        // 4. Determinación de la vista de contenido según el README
        $data['viewContent'] = __DIR__ . "/../../views/dashboard/{$rol}.php";

        // 5. Inyección de datos y carga del Layout "Pegamento"
        extract($data);
        require_once __DIR__ . '/../../views/layouts/layout.php';
    }
}
    echo "404 - Página no encontrada";
