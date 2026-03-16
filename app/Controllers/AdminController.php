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
 * - Gestión de usuarios del sistema
 * - Carga masiva de datos
 */
class AdminController {

    /**
     * 1. DASHBOARD ADMIN (HOME)
     */
    public function index() {
        // Inicializar modelo
        $personaModel = new Persona();
        
        // Calcular pendientes para el sidebar
        $_SESSION['pendientes_count'] = $personaModel->contarPendientes();

        $title = "Panel Administrativo";
        $active = "dashboard";

        // Cargar vistas
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        
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

        /* 4. INSERTAR */
        if ($usuarioModel->create($data)) {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=creado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=db_error');
        }
        exit;

        if ($usuarioModel->create($data)) {
        
        // ENVIAR CORREO
        $mailer = new CorreoService();
        $mailer->enviarCredenciales($data['correo'], $data['nombres'], $_POST['password']);
        
        header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=creado_y_notificado');
        }

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
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=actualizado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=db_error');
        }
        exit;

        if ($usuarioModel->create($data)) {
        
        // ENVIAR CORREO
        $mailer = new CorreoService();
        $mailer->enviarCredenciales($data['correo'], $data['nombres'], $_POST['password']);
        
        header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=creado_y_notificado');
        }
    }

    /**
     * Activa o Desactiva un usuario (Toggle)
     */
public function cambiarEstadoUsuario($id) {
        if (!is_numeric($id)) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }

        // 1. OBTENER INFORMACIÓN DEL OBJETIVO
        $usuarioModel = new Usuario();
        $targetUser = $usuarioModel->getById($id);

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
        if ($targetUser['rol'] === 'admin') {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Por seguridad, no puedes desactivar a otro Administrador. Contacta a soporte TI.'];
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
    public function procesarCarga() {

        /* 1. VALIDAR SUBIDA */
        if (!isset($_FILES['archivo_excel']) || $_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
            header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva?error=subida_fallida');
            exit;
        }

        /* 2. VALIDAR EXTENSIÓN */
        $ext = strtolower(pathinfo($_FILES['archivo_excel']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva?error=formato');
            exit;
        }

        /* 3. PROCESAR ARCHIVO */
        $servicio  = new ImportService();
        $resultado = $servicio->procesarArchivo($_FILES['archivo_excel']['tmp_name']);

       /* 4. ERROR FATAL */
        if (isset($resultado['error_fatal'])) {
            $_SESSION['error_carga'] = $resultado['error_fatal'];
            header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva');
            exit;
        }

        /* 5. ÉXITO Y OBSERVACIONES (DUPLICADOS) */
        $msg = sprintf(
            'Proceso terminado. Nuevos: %d, Duplicados: %d',
            $resultado['nuevos'],
            $resultado['omitidos'] ?? 0
        );

        $urlRedireccion = BASE_URL . '/dashboard/admin/carga-masiva?success=' . urlencode($msg);

        // Si el ImportService detectó duplicados o filas vacías, armamos la alerta amarilla
        if (!empty($resultado['errores'])) {
            // LÓGICA SENIOR: Los navegadores bloquean URLs muy largas. 
            // Si hay más de 50 errores, cortamos la lista para que el sistema no colapse.
            $listaErrores = $resultado['errores'];
            if (count($listaErrores) > 50) {
                $listaErrores = array_slice($listaErrores, 0, 50);
                $listaErrores[] = "...y otros " . (count($resultado['errores']) - 50) . " registros omitidos más.";
            }
            
            // Adjuntamos la lista codificada a la URL para que tu vista la lea en el $_GET['warning']
            $urlRedireccion .= '&warning=' . urlencode(json_encode($listaErrores));
        }

        header('Location: ' . $urlRedireccion);
        exit;
    }   
}