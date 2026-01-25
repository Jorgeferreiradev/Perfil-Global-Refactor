<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Periodo;
use App\Models\Asistencia;
use DateTime;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class EventoController {

    /* =======================
       LISTAR EVENTOS
    ======================= */
    public function index() {
        $model = new Evento();

        $filtros = [
            'linea'    => $_GET['linea'] ?? null,
            'programa' => $_GET['programa'] ?? null,
            'fecha'    => $_GET['fecha'] ?? null,
            'busqueda' => $_GET['busqueda'] ?? null
        ];

        $eventos   = $model->all($filtros);
        $lineas    = $model->getLineas();
        $programas = $model->getProgramas();

        $title = "Gestión de Eventos";
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/events/index.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /* =======================
       CREAR EVENTO
    ======================= */
    public function store() {
        // Validación de fecha pasada
        $fechaEvento = new DateTime($_POST['fecha_inicio'].' '.$_POST['hora_inicio']);
        $ahora = new DateTime();

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
        $model->create($data);

        header('Location: ' . BASE_URL . '/dashboard/eventos?success=creado');
        exit;
    }

    /* =======================
       EDITAR EVENTO
    ======================= */
public function update($id) {

    // 1. Obtener evento actual
    $model = new Evento();
    $eventoActual = $model->getById($id);

    if (!$eventoActual) {
        header('Location: ' . BASE_URL . '/dashboard/eventos?error=no_existe');
        exit;
    }

    // 2. Validar SOLO si cambió la fecha/hora
    $fechaNueva = new DateTime($_POST['fecha_inicio'] . ' ' . $_POST['hora_inicio']);
    $fechaActual = new DateTime($eventoActual['fecha_inicio'] . ' ' . $eventoActual['hora_inicio']);
    $ahora = new DateTime();

    if ($fechaNueva != $fechaActual && $fechaNueva < $ahora) {
        header('Location: ' . BASE_URL . '/dashboard/eventos?error=fecha_pasada');
        exit;
    }

    // 3. Preparar datos
    $data = [
        'nombre_evento'        => trim($_POST['nombre_evento']),
        'id_linea_accion'      => $_POST['linea_accion'],
        'programa_responsable' => $_POST['programa_responsable'],
        'sede'                 => $_POST['sede'],
        'fecha_inicio'         => $_POST['fecha_inicio'],
        'hora_inicio'          => $_POST['hora_inicio'],
        'fecha_final'          => $_POST['fecha_final'],
        'hora_final'           => $_POST['hora_final']
    ];

    // 4. Guardar
    if ($model->update($id, $data)) {
        header('Location: ' . BASE_URL . '/dashboard/eventos?success=actualizado');
    } else {
        header('Location: ' . BASE_URL . '/dashboard/eventos?error=update');
    }
    exit;
}


    /* =======================
       ELIMINAR EVENTO (SOFT)
    ======================= */
    public function eliminar($id) {
        $model = new Evento();

        if ($model->delete($id)) {
            header('Location: ' . BASE_URL . '/dashboard/eventos?success=eliminado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/eventos?error=db');
        }
        exit;
    }

    /* =======================
       VER ASISTENTES
    ======================= */
    public function verAsistentes($id) {
        $eventoModel = new Evento();
        $evento = $eventoModel->getById($id);

        if (!$evento) {
            die("Evento no existe");
        }

        $asistentes = (new Asistencia())->getByEvento($id);

        $title = "Asistentes - " . $evento['nombre_evento'];
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/events/asistentes.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /* =======================
       MOSTRAR QR
    ======================= */
    public function mostrarQR($token) {
        $evento = (new Evento())->getByToken($token);
        if (!$evento) die("Token inválido");

        $urlAsistencia = BASE_URL . "/asistencia/" . $token;

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
        ]);

        $qrImage = (new QRCode($options))->render($urlAsistencia);

        require_once __DIR__ . '/../../resources/views/events/qr_view.php';
    }
}
