<?php
// --- MODO DEBUG: ACTIVADO ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ----------------------------



// 1. Inicialización
session_start();

// Cargar librerías de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// --- [CORRECCIÓN CRÍTICA] Cargar Configuración Global (BASE_URL) ---
// Esto debe ir ANTES de cualquier ruta o vista
require_once __DIR__ . '/../config/config.php'; 

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->safeLoad();

// 2. Instancia del Router
$router = new \Bramus\Router\Router();

// Namespaces (Simplificados para evitar errores de sintaxis)
$controllers = 'App\Controllers';
$middleware  = 'App\Middleware';

// --- DEFINICIÓN DE RUTAS ---

// A. RUTAS PÚBLICAS (No requieren sesión)
// ---------------------------------------

// Autenticación
$router->get('/', "$controllers\AuthController@showLogin");
$router->get('/login', "$controllers\AuthController@showLogin");
$router->post('/auth/login', "$controllers\AuthController@login");
$router->get('/logout', "$controllers\AuthController@logout");

// Recuperación de Contraseña (Lo agregamos en el paso anterior)
$router->get('/auth/forgot-password', "$controllers\AuthController@showForgotPassword");
$router->post('/auth/recovery', "$controllers\AuthController@sendRecoveryLink");

// Asistencia Pública (Estudiantes escaneando QR)
$router->get('/asistencia/{token}', "$controllers\AsistenciaController@vistaRegistro");
$router->post('/asistencia/registrar', "$controllers\AsistenciaController@registrar");


// B. RUTAS PROTEGIDAS (Requieren Login)
// -------------------------------------
$router->mount('/dashboard', function () use ($router, $controllers, $middleware) {

    // 1. Middleware Global de Sesión (Verifica que estés logueado)
    $router->before('GET|POST', '/.*', "$middleware\SessionMiddleware@handle");

    // 2. Dashboard Home (Redirige según rol)
    $router->get('/', "$controllers\DashboardController@index");

    // 3. MÓDULO DE EVENTOS (Disponible para Admin y Monitor)
    // [AGREGADO] Estas rutas faltaban para poder crear eventos y ver QRs
    $router->mount('/eventos', function () use ($router, $controllers) {
        $router->get('/', "$controllers\EventoController@index");          // Listar
        $router->post('/crear', "$controllers\EventoController@store");    // Guardar nuevo
        $router->get('/qr/{token}', "$controllers\EventoController@mostrarQR"); // Ver QR
        $router->get('/asistentes/{id}', "$controllers\EventoController@verAsistentes"); // Ver lista
    });

    // 4. ZONA ADMIN (Solo rol 'admin')
    $router->mount('/admin', function () use ($router, $controllers) {

        // Middleware de Rol: Solo deja pasar si es admin
        $router->before('GET|POST', '/.*', function () {
            (new \App\Middleware\RoleMiddleware())->handle('admin');
        });

        // Home Admin
        $router->get('/', "$controllers\AdminController@index");

        // Gestión de Usuarios
        $router->get('/usuarios', "$controllers\AdminController@gestionarUsuarios");
        $router->post('/usuarios/guardar', "$controllers\AdminController@guardarUsuario");
        $router->get('/usuarios/eliminar/{id}', "$controllers\AdminController@eliminarUsuario");

        // Carga Masiva
        $router->get('/carga-masiva', "$controllers\AdminController@vistaCargaMasiva");
        $router->post('/carga-masiva/procesar', "$controllers\AdminController@procesarCarga");

        // Reportes Globales
        // Nota: Si quieres que el monitor también vea reportes, mueve esto fuera del mount '/admin'
        $router->mount('/reportes', function () use ($router, $controllers) {
            $router->get('/', "$controllers\ReporteController@index");
            $router->post('/descargar', "$controllers\ReporteController@descargarMatriz");
        });
    });

    // 5. ZONA MONITOR (Opcional, si tiene lógica exclusiva)
    $router->mount('/monitor', function () use ($router, $controllers) {
        $router->get('/', "$controllers\MonitorController@index");
    });
});

// Manejo de Error 404
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - Página no encontrada</h1><p>Verifica la URL ingresada.</p>';
});

// Ejecutar el Router
$router->run();