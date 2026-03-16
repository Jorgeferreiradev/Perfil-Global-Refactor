<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Persona;
use App\Models\Asistencia;
use DateTime;
use PDOException; // Importante para capturar el error de base de datos

class AsistenciaController {

    // =========================================================
    // 1. VISTA AL ESCANEAR EL QR
    // =========================================================
    public function vistaRegistro($token) {
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);

        // 1. Validar que el evento exista
        if (!$evento) {
            $this->mostrarError("Enlace Inválido", "Este código QR no está asociado a ningún evento activo.");
            return;
        }

        // 2. Validar Fechas (Aseguramos zona horaria)
        date_default_timezone_set('America/Bogota'); 
        
        $ahora  = new DateTime();
        $inicio = new DateTime($evento['fecha_inicio'] . ' ' . $evento['hora_inicio']);
        $fin    = new DateTime($evento['fecha_final'] . ' ' . $evento['hora_final']);

        // CASO A: Aún no empieza
        if ($ahora < $inicio) {
            $this->mostrarError("Evento No Iniciado", "El registro de asistencia se habilitará el " . $inicio->format('d/m/Y') . " a las " . $inicio->format('h:i A'));
            return;
        }

        // CASO B: Ya terminó
        if ($ahora > $fin) {
            $this->mostrarError("Evento Finalizado", "El tiempo para registrar asistencia terminó el " . $fin->format('d/m/Y') . " a las " . $fin->format('h:i A'));
            return;
        }

        // CASO C: Todo correcto -> Mostramos formulario
        $title = "Registro - " . $evento['nombre_evento'];
        require_once __DIR__ . '/../../resources/views/public/registro.php';
    }

    // =========================================================
    // 2. PROCESAR LA CÉDULA INGRESADA
    // =========================================================
    public function registrar() {
        $token = $_POST['token'] ?? '';
        $doc   = trim($_POST['documento'] ?? '');

        // VALIDACIÓN SENIOR DE LONGITUD
        if (strlen($doc) < 6 || strlen($doc) > 15) {
            // Regresamos al formulario con error específico
            header('Location: ' . BASE_URL . '/asistencia/' . $token . '?error=longitud_invalida');
            exit;
        }


        if (empty($token) || empty($doc)) die("Datos incompletos.");

        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);
        
        if (!$evento) die("Evento no válido.");

        $personaModel = new Persona();
        $persona = $personaModel->getByDocumento($doc);

        // CASO 1: NO EXISTE -> Mandar al Formulario Manual
        if (!$persona) {
            header('Location: ' . BASE_URL . '/asistencia/nuevo/' . $token . '/' . $doc);
            exit;
        }

        // CASO 2: EXISTE -> Procesar Asistencia
        // (Aunque esté pendiente, permitimos registrar asistencia al evento actual)
        $this->procesarAsistencia($evento, $persona['id'], $token, $persona['nombres']);
    }

    // =========================================================
    // 3. VISTA REGISTRO MANUAL (AMARILLA)
    // =========================================================
    public function vistaRegistroManual($token, $documento) {
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);
        
        if(!$evento) {
            $this->mostrarError("Error", "Evento no válido.");
            return;
        }

        $title = "Datos Nuevos - " . $evento['nombre_evento'];
        require_once __DIR__ . '/../../resources/views/public/registro_manual.php';
    }

    // =========================================================
    // 4. GUARDAR DATOS MANUALES (CON CORRECCIÓN DE BUG DUPLICADO)
    // =========================================================
public function guardarManual() {
        // 1. Recogida básica
        $token = $_POST['token'];
        $idTipo = (int) ($_POST['id_tipo'] ?? 5);

        // 2. SANITIZACIÓN AGRESIVA (Data Hygiene)
        // Eliminamos espacios al inicio/final y dobles espacios internos
        $nombres = trim(preg_replace('/\s+/', ' ', $_POST['nombres']));
        $apellidos = trim(preg_replace('/\s+/', ' ', $_POST['apellidos']));
        $documento = trim($_POST['documento']); // Solo números y sin espacios
        
        // Atrapamos el celular y el tipo de documento real ---
        $celular = trim($_POST['celular'] ?? null); 
        $tipoDoc = $_POST['tipo_documento'] ?? 'CC'; 
        // -----------------------------------------------------------------
                
        // VALIDACIÓN SENIOR DE LONGITUD
        if (strlen($documento) < 6 || strlen($documento) > 15) {
            die("Error: El documento debe tener entre 6 y 15 dígitos reales.");
        }

        // Limpieza de email
        $correoRaw = trim($_POST['correo']);
        $correoLimpio = filter_var($correoRaw, FILTER_SANITIZE_EMAIL);

        // 3. Validación de Email Real
        if (!filter_var($correoLimpio, FILTER_VALIDATE_EMAIL)) {
            // Si el correo está mal, lo devolvemos al formulario (puedes crear una vista de error o redirigir)
            die("Error: El formato del correo electrónico no es válido.");
        }

        $estado = ($idTipo === 99) ? 'activo' : 'pendiente';

        $datos = [
            'tipo_doc'  => $tipoDoc, // Guardamos el tipo de documento real
            'documento' => $documento,
            'nombres'   => strtoupper($nombres), // Convertimos a mayúsculas limpias
            'apellidos' => strtoupper($apellidos),
            'correo'    => $correoLimpio,
            'celular'  => $celular,
            'id_tipo'   => $idTipo,
            'estado'    => $estado
        ];

        $personaModel = new Persona();
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);

        // --- BLINDAJE CONTRA DOBLE CLIC (Try-Catch) ---
        try {
            $idPersona = $personaModel->createManual($datos);
        } catch (PDOException $e) {
            if ($e->getCode() == '23000') {
                $existente = $personaModel->getByDocumento($datos['documento']);
                if ($existente) {
                    $idPersona = $existente['id'];
                } else {
                    die("Error crítico de base de datos: " . $e->getMessage());
                }
            } else {
                throw $e;
            }
        }

        $this->procesarAsistencia($evento, $idPersona, $token, $datos['nombres']);
    }
        // Una vez tenemos el ID (nuevo o recuperado), registramos la asistencia


    // =========================================================
    // FUNCIONES PRIVADAS (HELPERS)
    // =========================================================
    private function procesarAsistencia($evento, $idPersona, $token, $nombrePersona) {
        $asistenciaModel = new Asistencia();
        
        // Evitar duplicados de asistencia en el mismo evento
        if ($asistenciaModel->yaRegistrado($evento['id_evento'], $idPersona)) {
            header('Location: ' . BASE_URL . '/asistencia/' . $token . '?error=duplicado');
            exit;
        }

        // Registrar
        $asistenciaModel->registrar([
            'id_evento'  => $evento['id_evento'],
            'persona_id' => $idPersona,
            'id_periodo' => $evento['id_periodo']
        ]);

        // Redirigir con éxito
        header('Location: ' . BASE_URL . '/asistencia/' . $token . '?success=1&nombre=' . urlencode($nombrePersona));
        exit;
    }

    private function mostrarError($titulo, $mensaje) {
        require_once __DIR__ . '/../../resources/views/public/error_asistencia.php';
        exit;
    }
}