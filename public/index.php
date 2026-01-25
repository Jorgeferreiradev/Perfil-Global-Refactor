<?php
// --- MODO DEBUG: ACTIVADO ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ----------------------------

// 1. Inicialización
session_start();

// Autoload de Composer
require_once __DIR__ . '/../vendor/autoload.php';

// --- Cargar Configuración Global ---
require_once __DIR__ . '/../config/config.php';

// Variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->safeLoad();

// 2. Instancia del Router
$router = new \Bramus\Router\Router();

// Namespaces
$controllers = 'App\Controllers';
$middleware  = 'App\Middleware';


// =======================================================
// A. RUTAS PÚBLICAS (SIN LOGIN)
// =======================================================

// Login
$router->get('/', "$controllers\AuthController@showLogin");
$router->get('/login', "$controllers\AuthController@showLogin");
$router->post('/auth/login', "$controllers\AuthController@login");
$router->get('/logout', "$controllers\AuthController@logout");

// Recuperación de contraseña
$router->get('/auth/forgot-password', "$controllers\AuthController@showForgotPassword");
$router->post('/auth/recovery', "$controllers\AuthController@sendRecoveryLink");

// Registro de asistencia pública (QR)
$router->get('/asistencia/{token}', "$controllers\AsistenciaController@vistaRegistro");
$router->post('/asistencia/registrar', "$controllers\AsistenciaController@registrar");


// =======================================================
// B. RUTAS PROTEGIDAS (CON LOGIN)
// =======================================================
$router->mount('/dashboard', function () use ($router, $controllers, $middleware) {

    // Middleware de sesión (obligatorio)
    $router->before('GET|POST', '/.*', "$middleware\SessionMiddleware@handle");

    // Dashboard principal
    $router->get('/', "$controllers\DashboardController@index");


    // ===================================================
    // 1. MÓDULO DE EVENTOS
    // ===================================================
    $router->mount('/eventos', function () use ($router, $controllers) {

        // --- RUTAS EXISTENTES ---
        $router->get('/', "$controllers\EventoController@index");              // Listar eventos
        $router->post('/crear', "$controllers\EventoController@store");        // Crear evento
        $router->get('/qr/{token}', "$controllers\EventoController@mostrarQR");// Mostrar QR
        $router->get('/asistentes/{id}', "$controllers\EventoController@verAsistentes");

        // --- 🔥 NUEVAS RUTAS (EDITAR / ELIMINAR) ---
        // Se usa POST para editar (buena práctica con formularios)
        $router->post('/editar/{id}', "$controllers\EventoController@update");

        // Soft delete (solo cambia estado)
        $router->get('/eliminar/{id}', "$controllers\EventoController@eliminar");
    });


    // ===================================================
    // 2. ZONA ADMIN (SOLO ADMIN)
    // ===================================================
    $router->mount('/admin', function () use ($router, $controllers) {

        // Middleware de rol
        $router->before('GET|POST', '/.*', function () {
            (new \App\Middleware\RoleMiddleware())->handle('admin');
        });

        // Home admin
        $router->get('/', "$controllers\AdminController@index");

        // Usuarios
        $router->get('/usuarios', "$controllers\AdminController@gestionarUsuarios");
        $router->post('/usuarios/guardar', "$controllers\AdminController@guardarUsuario");
        $router->get('/usuarios/eliminar/{id}', "$controllers\AdminController@eliminarUsuario");

        // Carga masiva
        $router->get('/carga-masiva', "$controllers\AdminController@vistaCargaMasiva");
        $router->post('/carga-masiva/procesar', "$controllers\AdminController@procesarCarga");

        // Reportes
        $router->mount('/reportes', function () use ($router, $controllers) {
            $router->get('/', "$controllers\ReporteController@index");
            $router->post('/descargar', "$controllers\ReporteController@descargarMatriz");
        });
    });


    // ===================================================
    // 3. ZONA MONITOR (OPCIONAL)
    // ===================================================
    $router->mount('/monitor', function () use ($router, $controllers) {
        $router->get('/', "$controllers\MonitorController@index");
    });
});


// =======================================================
// ERROR 404
// =======================================================
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - Página no encontrada</h1><p>Verifica la URL ingresada.</p>';
});

// Ejecutar Router
$router->run();
