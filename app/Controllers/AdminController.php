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
    /* =====================================================
     * ========== MODO DIOS: GESTIÓN DE SEMESTRES ==========
     * ===================================================== */

    

    /**
     * SIMULADOR: Muestra qué pasaría si se cierra el semestre, sin alterar la BD.
     */
    public function simularCierreSemestre() {
        $pdo = \Config\Database::getInstance();
        try {
            $stmt = $pdo->query("SELECT * FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $periodoActual = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$periodoActual) throw new \Exception("No hay un periodo activo para simular.");

            // Calcular el siguiente usando la función auxiliar (Helpers)
            $nuevoNombre = $this->calcularSiguienteSemestre($periodoActual['nombre_periodo']);

            $mensaje = "🔍 SIMULACIÓN: Si haces clic en Forzar Cierre, el periodo '{$periodoActual['nombre_periodo']}' pasará a Histórico, y se abrirá el nuevo periodo en blanco llamado: '{$nuevoNombre['nombre']}'.";
            
            // Enviamos el mensaje como 'warning' o 'info' para que destaque en azul/amarillo
            header('Location: ' . BASE_URL . '/dashboard/admin?warning=' . urlencode($mensaje));
            exit;
        } catch (\Throwable $e) {
            header('Location: ' . BASE_URL . '/dashboard/admin?error=' . urlencode('Error en simulación: ' . $e->getMessage()));
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
            header('Location: ' . BASE_URL . '/dashboard/admin?success=' . urlencode("✅ Semestre cerrado. Nuevo semestre {$nuevo['nombre']} abierto con éxito."));
            exit;
        } catch (\Throwable $e) { 
            if ($pdo->inTransaction()) $pdo->rollBack();
            header('Location: ' . BASE_URL . '/dashboard/admin?error=' . urlencode('Error CRÍTICO: ' . $e->getMessage()));
            exit;
        }
    }


    /** se deja un mes de gracia para cierre de bloque semestral.
     * HELPER PRIVADO: Calcula las nuevas fechas (Feb-Jul y Ago-Ene)
     */
    private function calcularSiguienteSemestre($nombreActual) {
        // Extraemos el año y el número de semestre actual
        if (preg_match('/Año\s+(\d{4})\s*.*Semestre\s+(I{1,2})/ui', $nombreActual, $matches)) {
            $añoActual = (int) $matches[1];
            $semestreActual = strtoupper($matches[2]);
        } else {
            throw new \Exception("El formato del nombre '{$nombreActual}' no es válido. Debe contener 'Año YYYY' y 'Semestre I' o 'II'.");
        }

        if ($semestreActual === 'I') {
            // Si estamos en I (Feb-Jul), pasamos al II (Ago-Ene)
            $nuevoAño = $añoActual;
            $nuevoSemestre = 'II';
            $meses = '(Ago - Ene)';
            $fini = "{$nuevoAño}-08-01";
            $ffin = ($nuevoAño + 1) . "-01-31"; // Termina en enero del SIGUIENTE año
        } else {
            // Si estamos en II (Ago-Ene), pasamos al I del próximo año (Feb-Jul)
            $nuevoAño = $añoActual + 1;
            $nuevoSemestre = 'I';
            $meses = '(Feb - Jul)';
            $fini = "{$nuevoAño}-02-01";
            $ffin = "{$nuevoAño}-07-31";
        }

        return [
            'nombre' => "Año {$nuevoAño} • Semestre {$nuevoSemestre} {$meses}",
            'fecha_inicio' => $fini,
            'fecha_fin' => $ffin
        ];
    }

    /**
     * Revierte el último cierre de semestre (Botón Deshacer)
     */
    public function deshacerCierreSemestre() {
        $pdo = \Config\Database::getInstance();
        $pdo->beginTransaction();

        try {
            // 1. Verificar si el periodo actual está vacío
            $stmtActivo = $pdo->query("SELECT id FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
            $periodoActivo = $stmtActivo->fetch(\PDO::FETCH_ASSOC);

            if ($periodoActivo) {
                $check = $pdo->prepare("SELECT COUNT(*) FROM historial_academico WHERE periodo_id = ?");
                $check->execute([$periodoActivo['id']]);
                
                if ($check->fetchColumn() > 0) {
                    throw new \Exception("No se puede revertir: Ya hay estudiantes matriculados en este nuevo semestre.");
                }

                // 2. Borrar el periodo nuevo (porque está vacío)
                $pdo->prepare("DELETE FROM periodos_academicos WHERE id = ?")->execute([$periodoActivo['id']]);
            }

            // 3. Reactivar el último periodo cerrado
            $pdo->query("
                UPDATE periodos_academicos 
                SET estado = 'activo' 
                WHERE estado = 'cerrado' 
                ORDER BY id DESC LIMIT 1
            ");

            $pdo->commit();
            header('Location: ' . BASE_URL . '/dashboard/admin?success=Cierre revertido con éxito');

        } catch (\Exception $e) {
            $pdo->rollBack();
            header('Location: ' . BASE_URL . '/dashboard/admin?error=' . urlencode($e->getMessage()));
        }
        exit;
    }
}