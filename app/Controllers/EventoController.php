<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Periodo;
use App\Models\Asistencia;
use DateTime;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class EventoController {

    public function index() {
        $model = new Evento();
        
        // Recoger Filtros de la URL ($_GET)
        $filtros = [
            'linea'    => $_GET['linea'] ?? null,
            'programa' => $_GET['programa'] ?? null,
            'fecha'    => $_GET['fecha'] ?? null,
            'busqueda' => $_GET['busqueda'] ?? null
        ];

        $eventos = $model->all($filtros);
        
        // Cargar listas para los selects de los filtros
        $lineas = $model->getLineas();
        $programas = $model->getProgramas();

        $title = "Gestión de Eventos";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/events/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function store() {
        // VALIDACIÓN DE SEGURIDAD (Fechas Pasadas)
        $fechaInicio = $_POST['fecha_inicio'];
        $horaInicio = $_POST['hora_inicio'];
        
        $fechaEvento = new DateTime("$fechaInicio $horaInicio");
        $ahora = new DateTime(); // Toma la hora de America/Bogota por config.php

        if ($fechaEvento < $ahora) {
            header('Location: ' . BASE_URL . '/dashboard/eventos?error=fecha_pasada');
            exit;
        }

        $data = [
            'nombre_evento'        => trim($_POST['nombre_evento']),
            'id_linea_accion'      => $_POST['linea_accion'],
            'programa_responsable' => $_POST['programa_responsable'],
            'sede'                 => $_POST['sede'],
            'fecha_inicio'         => $_POST['fecha_inicio'],
            'hora_inicio'          => $_POST['hora_inicio'],
            'fecha_final'          => $_POST['fecha_final'],
            'hora_final'           => $_POST['hora_final'],
            'id_periodo'           => (new Periodo())->getActivoId(),
            'creado_por'           => $_SESSION['user_id']
        ];

        $model = new Evento();
        if ($model->create($data)) {
            header('Location: ' . BASE_URL . '/dashboard/eventos?success=creado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/eventos?error=db');
        }
        exit;
    }

    // Método para VER ASISTENTES (Solución Punto 2)
    public function verAsistentes($id) {
        $eventoModel = new Evento();
        $evento = $eventoModel->getById($id);

        if(!$evento) die("Evento no existe");

        $asistenciaModel = new Asistencia();
        $asistentes = $asistenciaModel->getByEvento($id);

        $title = "Asistentes - " . $evento['nombre_evento'];
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/events/asistentes.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    public function mostrarQR($token) {
        $model = new Evento();
        $evento = $model->getByToken($token);
        if (!$evento) die("Token inválido");

        $urlAsistencia = BASE_URL . "/asistencia/" . $token;
        
        $options = new QROptions([
            'version' => 5,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel' => QRCode::ECC_L,
        ]);
        $qrImage = (new QRCode($options))->render($urlAsistencia);
        $title = "QR - " . $evento['nombre_evento'];
        
        require_once __DIR__ . '/../../resources/views/events/qr_view.php';
    }
}