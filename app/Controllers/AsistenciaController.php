<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Persona;
use App\Models\Asistencia;
use DateTime;

class AsistenciaController {

    // 1. Mostrar el formulario al escanear el QR
    public function vistaRegistro($token) {
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);

        // Validaciones de Seguridad (Senior Level)
        if (!$evento) {
            $this->mostrarError("Enlace inválido", "Este código QR no existe o ha sido eliminado.");
            return;
        }

        // Validar Fechas (CU-C01: El link es temporal)
        $ahora = new DateTime();
        $inicio = new DateTime($evento['fecha_inicio'] . ' ' . $evento['hora_inicio']);
        $fin    = new DateTime($evento['fecha_final'] . ' ' . $evento['hora_final']);

        if ($ahora < $inicio) {
            $this->mostrarError("Evento no iniciado", "El registro de asistencia aún no está habilitado.");
            return;
        }
        if ($ahora > $fin) {
            $this->mostrarError("Evento Finalizado", "El tiempo para registrar asistencia ha terminado.");
            return;
        }

        // Si todo está bien, mostramos la vista
        $title = "Registro - " . $evento['nombre_evento'];
        require_once __DIR__ . '/../../resources/views/public/registro.php';
    }

    // 2. Procesar el formulario (POST)
    public function registrar() {
        $token = $_POST['token'] ?? '';
        $doc   = trim($_POST['documento'] ?? '');

        if (empty($token) || empty($doc)) {
            die("Datos incompletos.");
        }

        // Buscamos el evento nuevamente
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);

        if (!$evento) die("Evento no válido.");

        // Buscamos a la persona en la BASE MAESTRA (CU-A02)
        $personaModel = new Persona();
        $persona = $personaModel->getByDocumento($doc);

        if (!$persona) {
            // Flujo Alternativo: Persona no existe en base de datos
            // Redirigimos con error para que el estudiante contacte a soporte o se registre manual (Futuro CU)
            header('Location: ' . BASE_URL . '/asistencia/' . $token . '?error=no_encontrado');
            exit;
        }

        // Verificamos duplicados
        $asistenciaModel = new Asistencia();
        if ($asistenciaModel->yaRegistrado($evento['id_evento'], $persona['id'])) {
            header('Location: ' . BASE_URL . '/asistencia/' . $token . '?error=duplicado');
            exit;
        }

        // Guardamos
        $asistenciaModel->registrar([
            'id_evento'  => $evento['id_evento'],
            'persona_id' => $persona['id'],
            'id_periodo' => $evento['id_periodo']
        ]);

        // Éxito
        header('Location: ' . BASE_URL . '/asistencia/' . $token . '?success=1&nombre=' . urlencode($persona['nombres']));
        exit;
    }

    private function mostrarError($titulo, $mensaje) {
        require_once __DIR__ . '/../../resources/views/public/error_asistencia.php';
        exit;
    }
}