<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Periodo;
use App\Models\Asistencia;
use DateTime;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class EventoController {

    /**
     * 🔥 HELPER SENIOR: Bloquea intentos de escritura en semestres históricos.
     * Convierte el sistema en modo "Solo Lectura" si el periodo no está activo.
     */
    private function protegerSemestreHistorico() {
        if (!isset($_SESSION['periodo_vista_estado']) || $_SESSION['periodo_vista_estado'] !== 'activo') {
            $_SESSION['flash'] = [
                'type' => 'danger', 
                'msg'  => '⛔ Acción denegada: El semestre seleccionado es histórico (Solo Lectura). No se permiten modificaciones.'
            ];
            header('Location: ' . BASE_URL . '/dashboard/eventos');
            exit;
        }
    }

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
        // 🛡️ CANDADO DE HIERRO: Evita inserciones en el pasado
        $this->protegerSemestreHistorico();

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
        // 🛡️ CANDADO DE HIERRO: Evita ediciones en el pasado
        $this->protegerSemestreHistorico();

        $model = new Evento();
        $eventoActual = $model->getById($id);

        if (!$eventoActual) {
            header('Location: ' . BASE_URL . '/dashboard/eventos?error=no_existe');
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
            'hora_final'           => $_POST['hora_final']
        ];

        if ($model->update($id, $data)) {
            header('Location: ' . BASE_URL . '/dashboard/eventos?success=actualizado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/eventos?error=update');
        }
        exit;
    }

    /* =======================
       CAMBIAR ESTADO (ACTIVAR/DESACTIVAR)
    ======================= */
    public function cambiarEstado($id, $estado) {
        // 🛡️ CANDADO DE HIERRO: Evita reactivar/desactivar en el pasado
        $this->protegerSemestreHistorico();

        $estadosPermitidos = ['activo', 'inactivo'];
        if (!in_array($estado, $estadosPermitidos)) {
             header('Location: ' . BASE_URL . '/dashboard/eventos?error=estado_invalido');
             exit;
        }

        $model = new Evento();
        if ($model->cambiarEstado($id, $estado)) {
            $msg = ($estado == 'activo') ? 'activado' : 'desactivado';
            header('Location: ' . BASE_URL . '/dashboard/eventos?success=' . $msg);
        } else {
            header('Location: ' . BASE_URL . '/dashboard/eventos?error=db');
        }
        exit;
    }

    /* =======================
       VER ASISTENTES (Permitido siempre)
    ======================= */
    public function verAsistentes($id) {
        // Aquí NO ponemos candado, porque consultar la historia sí está permitido.
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
       MOSTRAR QR (Permitido siempre)
    ======================= */
    public function mostrarQR($token) {
        // Aquí NO ponemos candado, porque ver un QR histórico puede ser útil.
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