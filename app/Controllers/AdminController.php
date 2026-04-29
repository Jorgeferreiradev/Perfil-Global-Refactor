<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Persona;      // Modelo para Aprobaciones
use App\Services\ImportService; // Servicio para Excel
use App\Services\CorreoService;
/**
 * Controlador de Administración
 * -----------------------------
 * Maneja TODAS las operaciones exclusivas del rol ADMIN:
 * - Aprobación de registros (Pendientes)
 * - Programas academicos
 * - Gestión de usuarios del sistema
 * - Carga masiva de datos
 */
class AdminController {

    /**
     * 1. DASHBOARD ADMIN (HOME)
     */
public function index() {
        $personaModel = new Persona();
        $_SESSION['pendientes_count'] = $personaModel->contarPendientes();

        // ---------------------------------------------------------
        // 🔥 LÓGICA SENIOR: Detección de Semestre Vencido
        // ---------------------------------------------------------
        $pdo = \Config\Database::getInstance();
        $stmt = $pdo->prepare("SELECT nombre_periodo, fecha_fin FROM periodos_academicos WHERE id = ?");
        $stmt->execute([$_SESSION['periodo_vista_id']]);
        $periodoActual = $stmt->fetch(\PDO::FETCH_ASSOC);

        $alertaCierre = false;
        $fechaActual = date('Y-m-d');
        
        // Comparamos si hoy es mayor a la fecha de fin estipulada
        if ($periodoActual && $fechaActual > $periodoActual['fecha_fin']) {
            $alertaCierre = true;
            $nombreSemestreVencido = $periodoActual['nombre_periodo'];
        }
        // ---------------------------------------------------------

        $title = "Panel Administrativo";
        $active = "dashboard";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        
        // Incluimos la vista, las variables $alertaCierre y $nombreSemestreVencido pasarán automáticamente
        if (file_exists(__DIR__ . '/../../resources/views/admin/index.php')) {
            require_once __DIR__ . '/../../resources/views/admin/index.php';
        } else {
            echo "<div class='p-5'><h1>Bienvenido al Panel Admin</h1></div>";
        }
        
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /* =====================================================
     * ============= MÓDULO DE APROBACIONES ================
     * ===================================================== */

    // VISTA DE LA TABLA PENDIENTES
    public function listaPendientes() {
        $model = new Persona();
        $pendientes = $model->getPendientes();
        
        // Actualizamos contador de sesión
        $_SESSION['pendientes_count'] = count($pendientes);

        $title  = "Aprobaciones Pendientes";
        $active = "pendientes";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/admin/pendientes.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    // ACCIÓN APROBAR
    public function aprobarUsuario($id) {
        $model = new Persona();
        if ($model->aprobar($id)) {
            $_SESSION['pendientes_count'] = $model->contarPendientes();
            header('Location: ' . BASE_URL . '/dashboard/admin/pendientes?msg=aprobado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/pendientes?error=db');
        }
        exit;
    }

    // ACCIÓN RECHAZAR
    public function rechazarUsuario($id) {
        $model = new Persona();
        if ($model->rechazar($id)) {
            $_SESSION['pendientes_count'] = $model->contarPendientes();
            header('Location: ' . BASE_URL . '/dashboard/admin/pendientes?msg=rechazado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/pendientes?error=db');
        }
        exit;
    }

    /* =====================================================
     * =============== GESTIÓN DE USUARIOS =================
     * ===================================================== */

    /**
     * Lista y gestiona usuarios
     */
    public function gestionarUsuarios() {
        $usuarioModel = new Usuario();

        // Obtener todos los usuarios menos el actual
        $usuarios = $usuarioModel->getAllExcept($_SESSION['user_id']);

        $title  = 'Gestión de Usuarios';
        $active = 'usuarios';

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/admin/usuarios.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /**
     * Guarda un nuevo usuario (CORREGIDO)
     */
    public function guardarUsuario() {
        /* 1. VALIDACIÓN BÁSICA */
        if (empty($_POST['nombres']) || empty($_POST['correo']) || empty($_POST['password'])) {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=campos_vacios');
            exit;
        }

        $usuarioModel = new Usuario();

        /* 2. VALIDAR DUPLICADO POR CORREO */
        if ($usuarioModel->exists($_POST['correo'])) {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=correo_duplicado');
            exit;
        }

        /* 3. PREPARAR DATOS LIMPIOS */
        $data = [
            'nombres'   => trim($_POST['nombres']),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'correo'    => trim($_POST['correo']),
            'password'  => password_hash($_POST['password'], PASSWORD_BCRYPT),
            'rol'       => $_POST['rol']
        ];

        /* 4. INSERTAR Y NOTIFICAR */
        if ($usuarioModel->create($data)) {
            // Enviar correo ANTES de redirigir
            try {
                $mailer = new CorreoService();
                $mailer->enviarBienvenidaUsuario($data['correo'], $data['nombres'], $_POST['password']);
                header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=creado_y_notificado');
            } catch (\Exception $e) {
                // Si el correo falla pero el usuario se creó, avisamos
                header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?warning=creado_pero_correo_fallo');
            }
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=db_error');
        }
        exit;
    }

    /**
     * Muestra el formulario de edición (AHORA ESTÁ EN SU LUGAR CORRECTO)
     */
    public function editarUsuario($id) {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->getById($id);

        if (!$usuario) {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=no_encontrado');
            exit;
        }

        $title  = 'Editar Usuario';
        $active = 'usuarios';

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/admin/usuarios_edit.php'; 
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /**
     * Procesa la actualización (AHORA ESTÁ EN SU LUGAR CORRECTO)
     */
    public function actualizarUsuario($id) {
        $usuarioModel = new Usuario();

        // 1. Validar que no exista el correo en OTRO usuario
        if ($usuarioModel->existsEmailExcept($_POST['correo'], $id)) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'El correo ya está en uso por otro usuario.'];
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios/editar/' . $id);
            exit;
        }

        // 2. Preparar datos
        $data = [
            'nombres'   => trim($_POST['nombres']),
            'apellidos' => trim($_POST['apellidos']),
            'correo'    => trim($_POST['correo']),
            'rol'       => $_POST['rol'],
            'password'  => !empty($_POST['password']) ? $_POST['password'] : null 
        ];

        // 3. Actualizar
        if ($usuarioModel->update($id, $data)) {
            // Nota: Usualmente no se envía contraseña por correo al actualizar a menos que lo pidas, 
            // lo dejamos simple por seguridad.
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=actualizado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=db_error');
        }
        exit;
    }

    /**
     * Activa o Desactiva un usuario (Toggle)
     */
public function cambiarEstadoUsuario($id) {
        if (!is_numeric($id)) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }
        // 🔥 ESCUDO DE PROTECCIÓN TOTAL
        if ($id == 1) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Acceso denegado: Usuario protegido por el núcleo del sistema.'];
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios');
            exit;
        }

        // 1. OBTENER INFORMACIÓN DEL OBJETIVO
        $usuarioModel = new Usuario();
        $targetUser = $usuarioModel->getByIdAll($id);

        if (!$targetUser) {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=no_encontrado');
            exit;
        }

        // 2. CANDADO DE SEGURIDAD (ANTI HARA-KIRI)
        if ($id == $_SESSION['user_id']) {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => '¡No puedes desactivar tu propia cuenta!'];
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios');
            exit;
        }

        // 3. CANDADO DE JERARQUÍA (ADMIN NO MATA ADMIN)
        // Si el usuario objetivo es ADMIN, prohibimos la acción
        if ($targetUser['rol'] === 'superadmin') {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Por seguridad, no puedes desactivar a otro SuperAdministrador. Contacta a soporte TI.'];
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios');
            exit;
        }
        
        // 4. EJECUTAR CAMBIO (Solo si pasó los filtros)
        if ($usuarioModel->toggleEstado($id)) {
            $nuevoEstado = ($targetUser['deleted_at'] === null) ? 'desactivado' : 'reactivado';
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => "Usuario $nuevoEstado correctamente."];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Error al cambiar estado.'];
        }
        
        header('Location: ' . BASE_URL . '/dashboard/admin/usuarios');
        exit;
    }

    /* =====================================================
     * ================= CARGA MASIVA ======================
     * ===================================================== */

    /**
     * Vista de carga masiva
     */
    public function vistaCargaMasiva() {
        $title  = 'Carga Masiva de Base de Datos';
        $active = 'carga-masiva';

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/admin/carga_masiva.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /**
     * Procesa el archivo Excel/CSV
     */
    /**
     * Procesa el archivo Excel/CSV
     */
    public function procesarCarga() {

        /* 1. VALIDAR SUBIDA */
        if (!isset($_FILES['archivo_excel']) || $_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
            header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva?error=subida_fallida');
            exit;
        }

        /* 2. VALIDAR EXTENSIÓN */
        $tmpName = $_FILES['archivo_excel']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['archivo_excel']['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva?error=formato');
            exit;
        }

        /* 🔥 3. CORTAFUEGOS SENIOR: VALIDAR CABECERAS (EVITAR CORRUPCIÓN) */
        if ($ext === 'csv') {
            $handle = fopen($tmpName, "r");
            $headers = fgetcsv($handle, 1000, ","); 
            fclose($handle);
            
            // Si en la primera fila no existe la columna "numero_documento" o "documento", bloqueamos.
            if (!in_array('numero_documento', $headers) && !in_array('documento', $headers) && !in_array('identificacion', $headers)) {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => '❌ Error de Seguridad: El archivo no es una plantilla de Personas válida. Faltan columnas como "numero_documento".'];
                header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva');
                exit;
            }
        }

        /* 4. PROCESAR ARCHIVO (Si pasó la seguridad) */
        $servicio  = new ImportService();
        $resultado = $servicio->procesarArchivo($tmpName);

       /* 5. ERROR FATAL */
        if (isset($resultado['error_fatal'])) {
            $_SESSION['error_carga'] = $resultado['error_fatal'];
            header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva');
            exit;
        }

        /* 6. ÉXITO Y OBSERVACIONES */
        $msg = sprintf(
            'Proceso terminado. Nuevos: %d, Duplicados: %d',
            $resultado['nuevos'],
            $resultado['omitidos'] ?? 0
        );

        $urlRedireccion = BASE_URL . '/dashboard/admin/carga-masiva?success=' . urlencode($msg);

        if (!empty($resultado['errores'])) {
            $listaErrores = $resultado['errores'];
            if (count($listaErrores) > 50) {
                $listaErrores = array_slice($listaErrores, 0, 50);
                $listaErrores[] = "...y otros " . (count($resultado['errores']) - 50) . " registros omitidos más.";
            }
            $urlRedireccion .= '&warning=' . urlencode(json_encode($listaErrores));
        }

        header('Location: ' . $urlRedireccion);
        exit;
    }
    /* =====================================================
     * ========== MODO DIOS: GESTIÓN DE SEMESTRES ==========
     * ===================================================== */

    

   /**
     * SIMULADOR (MODO API): Calcula el impacto y devuelve JSON para el Modal
     */
    public function simularCierreSemestre() {
        header('Content-Type: application/json'); // Respondemos en formato JSON
        $pdo = \Config\Database::getInstance();
        
        try {
            // 1. Obtener periodo actual
            $stmt = $pdo->query("SELECT * FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $periodoActual = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$periodoActual) throw new \Exception("No hay un periodo activo para simular.");
            $idPeriodo = $periodoActual['id'];

            // 2. Contar la historia (Eventos, Asistencias y Estudiantes del periodo)
            $totalEventos = $pdo->query("SELECT COUNT(*) FROM eventos WHERE id_periodo = $idPeriodo")->fetchColumn();
            $totalEstudiantes = $pdo->query("SELECT COUNT(*) FROM historial_academico WHERE periodo_id = $idPeriodo")->fetchColumn();
            
            // Asistencias unidas a los eventos de este periodo
            $totalAsist = $pdo->query("SELECT COUNT(*) FROM asistencias a INNER JOIN eventos e ON a.id_evento = e.id_evento WHERE e.id_periodo = $idPeriodo")->fetchColumn();

            // 3. Contar Pendientes de Aprobación
            $personaModel = new \App\Models\Persona();
            $pendientes = $personaModel->contarPendientes();

            // 4. Calcular el futuro
            $nuevoNombre = $this->calcularSiguienteSemestre($periodoActual['nombre_periodo']);

            // Devolver todo el paquete al Frontend
            echo json_encode([
                'success' => true,
                'semestre_actual' => $periodoActual['nombre_periodo'],
                'semestre_nuevo'  => $nuevoNombre['nombre'],
                'eventos'         => $totalEventos,
                'asistencias'     => $totalAsist,
                'estudiantes'     => $totalEstudiantes,
                'pendientes'      => $pendientes
            ]);
            exit;

        } catch (\Throwable $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    /**
     * CIERRE REAL: Fuerza el cierre del semestre actual y abre el siguiente
     */
    public function forzarCierreSemestre() {
        $pdo = \Config\Database::getInstance();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->query("SELECT * FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $periodoActual = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$periodoActual) throw new \Exception("No hay un periodo activo para cerrar.");

            // 1. Cerrar actual
            $pdo->prepare("UPDATE periodos_academicos SET estado = 'cerrado' WHERE id = ?")
                ->execute([$periodoActual['id']]);

            // 2. Calcular el siguiente
            $nuevo = $this->calcularSiguienteSemestre($periodoActual['nombre_periodo']);

            // 3. Crear el nuevo
            $insertStmt = $pdo->prepare("
                INSERT INTO periodos_academicos (nombre_periodo, fecha_inicio, fecha_fin, estado) 
                VALUES (:nom, :fini, :ffin, 'activo')
            ");
            $insertStmt->execute([
                ':nom'  => $nuevo['nombre'],
                ':fini' => $nuevo['fecha_inicio'],
                ':ffin' => $nuevo['fecha_fin']
            ]);

            $pdo->commit();

            // 🔥 SENIOR FIX: Actualizar la sesión al nuevo periodo para que los lentes viajen automáticamente
            $stmtActivo = $pdo->query("SELECT id, nombre_periodo, estado FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $nuevoActivo = $stmtActivo->fetch(\PDO::FETCH_ASSOC);
            $_SESSION['periodo_vista_id'] = $nuevoActivo['id'];
            $_SESSION['periodo_vista_nombre'] = $nuevoActivo['nombre_periodo'];
            $_SESSION['periodo_vista_estado'] = $nuevoActivo['estado'];

            header('Location: ' . BASE_URL . '/dashboard/admin/semestres?success=' . urlencode("✅ Semestre cerrado. Nuevo semestre {$nuevo['nombre']} abierto con éxito."));
            exit;
        } catch (\Throwable $e) { 
            if ($pdo->inTransaction()) $pdo->rollBack();
            header('Location: ' . BASE_URL . '/dashboard/admin/semestres?error=' . urlencode('Error CRÍTICO: ' . $e->getMessage()));
            exit;
        }
    }

    public function semestres() {
        $title = "Gestión Semestral";
        $active = "semestres"; // Para que el menú lateral se ilumine
        
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/admin/semestres.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }


 
    /**
     * HELPER PRIVADO: Calcula las nuevas fechas exactas (Ene-Jun y Jul-Dic)
     * facilitando las consultas de reportes semestrales.
     */
    private function calcularSiguienteSemestre($nombreActual) {
        if (preg_match('/Año\s+(\d{4})\s*.*Semestre\s+(I{1,2})/ui', $nombreActual, $matches)) {
            $añoActual = (int) $matches[1];
            $semestreActual = strtoupper($matches[2]);
        } else {
            throw new \Exception("El formato del nombre '{$nombreActual}' no es válido.");
        }

        if ($semestreActual === 'I') {
            // Si estamos en I (Ene-Jun), pasamos al II (Jul-Dic) del MISMO año
            $nuevoAño = $añoActual;
            $nuevoSemestre = 'II';
            $meses = '(Jul - Dic)';
            $fini = "{$nuevoAño}-07-01";
            $ffin = "{$nuevoAño}-12-31"; 
        } else {
            // Si estamos en II (Jul-Dic), pasamos al I (Ene-Jun) del PRÓXIMO año
            $nuevoAño = $añoActual + 1;
            $nuevoSemestre = 'I';
            $meses = '(Ene - Jun)';
            $fini = "{$nuevoAño}-01-01";
            $ffin = "{$nuevoAño}-06-30";
        }

        return [
            'nombre' => "Año {$nuevoAño} • Semestre {$nuevoSemestre} {$meses}",
            'fecha_inicio' => $fini,
            'fecha_fin' => $ffin
        ];
    }

    /**
     * Revierte el último cierre de semestre (Botón Deshacer) con Estadísticas y Protección Total
     */
    public function deshacerCierreSemestre() {
        $pdo = \Config\Database::getInstance();
        $pdo->beginTransaction();

        try {
            // 1. Verificar si el periodo actual existe
            $stmtActivo = $pdo->query("SELECT id FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $periodoActivo = $stmtActivo->fetch(\PDO::FETCH_ASSOC);

            if ($periodoActivo) {
                // 🔥 SENIOR FIX: Revisamos si ya hay estudiantes matriculados
                $checkEstudiantes = $pdo->prepare("SELECT COUNT(*) FROM historial_academico WHERE periodo_id = ?");
                $checkEstudiantes->execute([$periodoActivo['id']]);
                $totalEstudiantes = $checkEstudiantes->fetchColumn();
                
                // 🔥 SENIOR FIX: Revisamos si ya hay eventos creados
                $checkEventos = $pdo->prepare("SELECT COUNT(*) FROM eventos WHERE id_periodo = ?");
                $checkEventos->execute([$periodoActivo['id']]);
                $totalEventos = $checkEventos->fetchColumn();

                // Si hay CUALQUIER dato, detenemos el proceso con un mensaje súper claro para el usuario
                if ($totalEstudiantes > 0 || $totalEventos > 0) {
                    throw new \Exception("Acción bloqueada por seguridad: No puedes deshacer el cierre porque el semestre actual ya tiene registrados {$totalEventos} evento(s) y {$totalEstudiantes} estudiante(s). Debes eliminar o reasignar esos registros primero.");
                }

                // 2. Si el semestre está totalmente virgen, lo borramos con seguridad
                $pdo->prepare("DELETE FROM periodos_academicos WHERE id = ?")->execute([$periodoActivo['id']]);
            }

            // 3. Reactivar el último periodo cerrado
            $pdo->query("
                UPDATE periodos_academicos 
                SET estado = 'activo' 
                WHERE estado = 'cerrado' 
                ORDER BY id DESC LIMIT 1
            ");

            // 4. Obtener estadísticas del periodo reactivado para la alerta de éxito
            $stmtRev = $pdo->query("SELECT * FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $periodoRev = $stmtRev->fetch(\PDO::FETCH_ASSOC);
            
            if($periodoRev) {
                $idRev = $periodoRev['id'];
                $nombreRev = $periodoRev['nombre_periodo'];

                // Contamos qué acabamos de rescatar
                $totalEv = $pdo->query("SELECT COUNT(*) FROM eventos WHERE id_periodo = $idRev")->fetchColumn();
                $totalEst = $pdo->query("SELECT COUNT(*) FROM historial_academico WHERE periodo_id = $idRev")->fetchColumn();

                $pdo->commit();
                
                // Actualizar la sesión al periodo rescatado
                $_SESSION['periodo_vista_id'] = $periodoRev['id'];
                $_SESSION['periodo_vista_nombre'] = $periodoRev['nombre_periodo'];
                $_SESSION['periodo_vista_estado'] = $periodoRev['estado'];

                // Mensaje enriquecido
                $mensaje = "✅ Cierre revertido con éxito. Has regresado al semestre: '$nombreRev'. Tienes $totalEv eventos y $totalEst estudiantes activos nuevamente.";
                header('Location: ' . BASE_URL . '/dashboard/admin/semestres?success=' . urlencode($mensaje));
            } else {
                $pdo->commit();
                header('Location: ' . BASE_URL . '/dashboard/admin/semestres?success=' . urlencode('Cierre revertido, pero no se encontró un semestre anterior.'));
            }

        } catch (\Exception $e) {
            $pdo->rollBack();
            // Aquí es donde tu vista captura el "throw new Exception" y muestra el mensaje rojo bonito
            header('Location: ' . BASE_URL . '/dashboard/admin/semestres?error=' . urlencode($e->getMessage()));
        }
        exit;
    }
}