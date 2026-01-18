<?php
namespace App\Controllers;

use App\Services\ImportService;

class AdminController {

    // ... (métodos anteriores de carga masiva)

    /**
     * Muestra la lista de usuarios y el formulario de creación
     */
    public function gestionarUsuarios() {
        $title = "Gestión de Usuarios del Sistema";
        $active = "usuarios";
        
        // Obtener todos los usuarios (menos el propio admin para no auto-borrarse)
        $db = \Config\Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM usuarios_sistema WHERE deleted_at IS NULL AND id != :myId ORDER BY rol, apellidos");
        $stmt->execute([':myId' => $_SESSION['user_id']]);
        $usuarios = $stmt->fetchAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/layouts/sidebar.php';
        require_once __DIR__ . '/../../views/admin/usuarios.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    /**
     * Guarda un nuevo monitor, admin o dev
     */
    public function guardarUsuario() {
        // Validar campos
        if (empty($_POST['nombres']) || empty($_POST['correo']) || empty($_POST['password'])) {
            header('Location: /dashboard/admin/usuarios?error=campos_vacios');
            exit;
        }

        $nombres = trim($_POST['nombres']);
        $apellidos = trim($_POST['apellidos']);
        $correo = trim($_POST['correo']);
        $rol = $_POST['rol'];
        // Hash seguro de contraseña
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $db = \Config\Database::getInstance();

        // Verificar duplicados
        $check = $db->prepare("SELECT id FROM usuarios_sistema WHERE correo = :correo");
        $check->execute([':correo' => $correo]);
        if ($check->fetch()) {
            header('Location: /dashboard/admin/usuarios?error=correo_duplicado');
            exit;
        }

        // Insertar
        $sql = "INSERT INTO usuarios_sistema (nombres, apellidos, correo, password, rol) 
                VALUES (:nom, :ape, :cor, :pass, :rol)";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute([
            ':nom' => $nombres, 
            ':ape' => $apellidos, 
            ':cor' => $correo, 
            ':pass' => $password, 
            ':rol' => $rol
        ])) {
            header('Location: /dashboard/admin/usuarios?success=creado');
        } else {
            header('Location: /dashboard/admin/usuarios?error=db_error');
        }
    }

    /**
     * "Elimina" un usuario (Soft Delete)
     */
    public function eliminarUsuario($id) {
        // Validación de seguridad básica
        if (!is_numeric($id)) die("ID Inválido");

        $db = \Config\Database::getInstance();
        
        // Soft Delete: No borramos el registro, solo marcamos deleted_at
        // Esto mantiene la integridad referencial de los eventos que creó ese usuario.
        $stmt = $db->prepare("UPDATE usuarios_sistema SET deleted_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $id]);

        header('Location: /dashboard/admin/usuarios?success=eliminado');
    }


    public function vistaCargaMasiva() {
        $title = "Carga Masiva de Usuarios";
        $active = "carga-masiva"; // Para resaltar en sidebar
        
        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/layouts/sidebar.php';
        require_once __DIR__ . '/../../views/admin/carga_masiva.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function procesarCarga() {
        // 1. Validar que se subió un archivo
        if (!isset($_FILES['archivo_excel']) || $_FILES['archivo_excel']['error'] !== UPLOAD_ERR_OK) {
            header('Location: /dashboard/admin/carga-masiva?error=archivo_invalido');
            exit;
        }

        $archivo = $_FILES['archivo_excel'];
        $ext = pathinfo($archivo['name'], PATHINFO_EXTENSION);

        // 2. Validar extensión
        if (!in_array(strtolower($ext), ['xlsx', 'xls', 'csv'])) {
            header('Location: /dashboard/admin/carga-masiva?error=formato_incorrecto');
            exit;
        }

        // 3. Procesar usando el Servicio
        $importService = new ImportService();
        $resultado = $importService->procesarArchivo($archivo['tmp_name']);

        if (isset($resultado['error_fatal'])) {
            // Guardar error en sesión para mostrarlo
            $_SESSION['flash_error'] = $resultado['error_fatal'];
            header('Location: /dashboard/admin/carga-masiva');
            exit;
        }

        // 4. Éxito
        $msg = "Proceso finalizado. Nuevos: {$resultado['nuevos']}, Actualizados: {$resultado['actualizados']}";
        header('Location: /dashboard/admin/carga-masiva?success=' . urlencode($msg));
    }
}