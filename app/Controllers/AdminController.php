<?php
namespace App\Controllers;

use App\Models\Usuario;
use App\Services\ImportService;

/**
 * Controlador de Administración
 * -----------------------------
 * Maneja TODAS las operaciones exclusivas del rol ADMIN:
 * - Gestión de usuarios
 * - Carga masiva de datos
 * - Redirecciones y vistas administrativas
 */
class AdminController {

    /**
     * Dashboard Admin
     * No tiene vista propia: redirige al dashboard unificado
     */
    public function index() {
        header('Location: ' . BASE_URL . '/dashboard');
        exit; // SIEMPRE cortar ejecución tras header
    }

    /* =====================================================
     * =============== GESTIÓN DE USUARIOS =================
     * ===================================================== */

    /**
     * Lista y gestiona usuarios (excepto el admin logueado)
     */
    public function gestionarUsuarios() {
        $usuarioModel = new Usuario();

        // Obtener todos los usuarios menos el actual
        $usuarios = $usuarioModel->getAllExcept($_SESSION['user_id']);

        // Variables usadas por las vistas
        $title  = 'Gestión de Usuarios';
        $active = 'usuarios';

        // Render del layout
        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/admin/usuarios.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    /**
     * Guarda un nuevo usuario (admin o monitor)
     */
    public function guardarUsuario() {

        /* 1. VALIDACIÓN BÁSICA */
        if (
            empty($_POST['nombres']) ||
            empty($_POST['correo']) ||
            empty($_POST['password'])
        ) {
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
            'rol'       => $_POST['rol'] // admin | monitor
        ];

        /* 4. INSERTAR */
        if ($usuarioModel->create($data)) {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=creado');
        } else {
            header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?error=db_error');
        }
        exit;
    }

    /**
     * Eliminación lógica (soft delete) de usuario
     */
    public function eliminarUsuario($id) {
        if (!is_numeric($id)) {
            header('HTTP/1.1 400 Bad Request');
            exit;
        }

        $usuarioModel = new Usuario();
        $usuarioModel->softDelete($id);

        header('Location: ' . BASE_URL . '/dashboard/admin/usuarios?success=eliminado');
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
     * - Limpia datos
     * - Corrige caracteres
     * - Evita duplicados
     * - Inserta o actualiza
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

        /* 5. ÉXITO */
        $msg = sprintf(
            'Proceso terminado. Nuevos: %d, Actualizados: %d, Omitidos: %d',
            $resultado['nuevos'],
            $resultado['actualizados'],
            $resultado['omitidos'] ?? 0
        );

        header('Location: ' . BASE_URL . '/dashboard/admin/carga-masiva?success=' . urlencode($msg));
        exit;
    }
}
