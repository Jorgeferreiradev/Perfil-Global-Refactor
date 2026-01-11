<?php
namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Persona;
use App\Models\Log;

class AdminController {
    
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . $_ENV['APP_URL'] . '/login');
            exit;
        }
    }

    public function dashboard() {
        $nombre = $_SESSION['user_name'] ?? 'Administrador';
        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }

public function importarUsuarios() {
/* ESTO ES PARA DEPURAR:
    echo "<pre>";
    print_r($_FILES); // Ver si llega el archivo
    print_r($_POST);  // Ver si llega el resto
    echo "</pre>";
    die("Detenido para inspección");*/



    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
        $log = new \App\Models\Log();
        try {
            // 1. Cargar el archivo temporalmente
            $file = $_FILES['archivo_excel']['tmp_name'];
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $data = $spreadsheet->getActiveSheet()->toArray();

            // 2. Ejecutar la lógica en el modelo
            $personaModel = new \App\Models\Persona();
            $personaModel->importarMasivo($data);

            // 3. Registrar auditoría (Buena práctica senior)
            $total = count($data) - 1;
            $log->registrar($_SESSION['user_id'], "Carga masiva exitosa: $total registros.");
            
            $_SESSION['success'] = "¡Éxito! $total registros procesados.";
        } catch (\Exception $e) {
            $log->registrar($_SESSION['user_id'], "Error en carga: " . $e->getMessage());
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        
        // Redirección absoluta (el secreto para evitar el admin/admin)
        header('Location: ' . $_ENV['APP_URL'] . '/admin/dashboard');
        exit;
        }
    }
}
