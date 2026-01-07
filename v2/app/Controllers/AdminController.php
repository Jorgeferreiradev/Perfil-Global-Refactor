<?php
namespace App\Controllers;

// Importamos la librería de Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Log;

class AdminController {
    
    public function __construct() {
        // Seguridad Senior: Este constructor protege TODOS los métodos de esta clase
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
        // Verificamos que venga un archivo
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo_excel'])) {
            $file = $_FILES['archivo_excel']['tmp_name'];
            
            try {
                // Cargamos el Excel
                $spreadsheet = IOFactory::load($file);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Aquí procesaremos los datos fila por fila
                // El momentum sigue: en el próximo paso creamos el Modelo para guardar esto
                
                $_SESSION['success'] = "Archivo procesado: " . (count($data) - 1) . " filas detectadas.";
                header('Location: dashboard');
                exit;
                    try {
    // (Lógica de importación que ya tenemos...)
    $personaModel = new \App\Models\Persona();
    $personaModel->importarMasivo($data);

    // REGISTRO DE AUDITORÍA (Best Practice)
    $log = new Log();
    $cantidad = count($data) - 1;
    $mensajeLog = "Carga masiva realizada: se importaron $cantidad registros de estudiantes.";
    $log->registrar($_SESSION['user_id'], $mensajeLog);

    $_SESSION['success'] = "¡Importación exitosa!";
    header('Location: dashboard');
    exit;

} catch (\Exception $e) {
    // También es bueno registrar los fallos
    $log = new Log();
    $log->registrar($_SESSION['user_id'], "FALLO en carga masiva: " . $e->getMessage());
    
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header('Location: dashboard');
    exit;
}
            } catch (\Exception $e) {
                die("Error al procesar el Excel: " . $e->getMessage());
            }
        }
    }
}