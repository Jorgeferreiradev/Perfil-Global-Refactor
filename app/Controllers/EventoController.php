<?php
namespace App\Controllers;

use App\Models\Evento;
use Chillerlan\QRCode\QRCode; // Librería instalada via Composer

class EventoController {
    
    public function index() {
        $eventoModel = new Evento();
        $eventos = $eventoModel->getAllByUser($_SESSION['user_id']);
        
        $title = "Gestión de Eventos";
        $active = "eventos";

        // Cargar vista de lista (table)
        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/layouts/sidebar.php';
        require_once __DIR__ . '/../../views/events/index.php'; // Crea este archivo con la tabla HTML
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function store() {
        // 1. Recibir datos del formulario (POST)
        // Validar que no vengan vacíos (Práctica Clean Code: Fail Fast)
        if (empty($_POST['nombre_evento'])) {
            die("Error: Nombre requerido");
        }

        // 2. Generar Token Único para el QR
        // Usamos random_bytes para alta entropía criptográfica
        $token = bin2hex(random_bytes(16)); 

        $data = [
            'nombre'       => $_POST['nombre_evento'],
            'token'        => $token,
            'linea'        => $_POST['linea_accion'],
            'fecha_inicio' => $_POST['fecha_inicio'],
            'fecha_final'  => $_POST['fecha_final'],
            'hora_inicio'  => $_POST['hora_inicio'],
            'hora_final'   => $_POST['hora_final'],
            'sede'         => $_POST['sede']
        ];

        // 3. Guardar en BD
        $model = new Evento();
        $idEvento = $model->create($data);

        // 4. Generar la imagen del QR (opcional guardarla en disco o generarla al vuelo)
        // Aquí solo redirigimos, el QR se verá en la vista 'detalle'
        header('Location: /dashboard/eventos?success=creado');
    }

    // Método para mostrar el QR en pantalla
    public function mostrarQR($token) {
        // La URL que el estudiante escaneará
        $urlAsistencia = "https://tudominio.edu.co/asistencia/" . $token;
        
        // Renderizar QR
        echo '<img src="'.(new QRCode)->render($urlAsistencia).'" alt="QR Code" />';
    }
}