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
// A. RUTAS PÚBLICAS (SIN LOGIN - ACCESO LIBRE)
// =======================================================

// 1. Autenticación
$router->get('/', "$controllers\AuthController@showLogin");
$router->get('/login', "$controllers\AuthController@showLogin");
$router->post('/auth/login', "$controllers\AuthController@login");
$router->get('/logout', "$controllers\AuthController@logout");

$router->get('/auth/forgot-password', "$controllers\AuthController@showForgotPassword");
$router->post('/auth/recovery', "$controllers\AuthController@sendRecoveryLink");

// 2. Asistencia Pública
// A. Rutas Específicas
$router->get('/asistencia/nuevo/{token}/{documento}', "$controllers\AsistenciaController@vistaRegistroManual");
$router->post('/asistencia/guardar-manual', "$controllers\AsistenciaController@guardarManual");
$router->post('/asistencia/registrar', "$controllers\AsistenciaController@registrar");

// B. Ruta Genérica (Token)
$router->get('/asistencia/{token}', "$controllers\AsistenciaController@vistaRegistro");


// =======================================================
// B. RUTAS PROTEGIDAS (CON LOGIN - DASHBOARD)
// =======================================================
$router->mount('/dashboard', function () use ($router, $controllers, $middleware) {

    // Middleware de sesión (obligatorio para todo lo de abajo)
    $router->before('GET|POST', '/.*', "$middleware\SessionMiddleware@handle");

    // Dashboard principal (Admin y Monitor)
    $router->get('/', "$controllers\DashboardController@index");

    // ---------------------------------------------------
    // 1. MÓDULO DE EVENTOS (Acceso Mixto)
    // ---------------------------------------------------
    $router->mount('/eventos', function () use ($router, $controllers) {
        $router->get('/', "$controllers\EventoController@index");          // Ver lista
        $router->post('/crear', "$controllers\EventoController@store");    // Guardar nuevo
        $router->get('/qr/{token}', "$controllers\EventoController@mostrarQR");
        $router->get('/asistentes/{id}', "$controllers\EventoController@verAsistentes");
        $router->post('/editar/{id}', "$controllers\EventoController@update");
        $router->get('/estado/{id}/{estado}', "$controllers\EventoController@cambiarEstado");
    });

    // ---------------------------------------------------
    // 2. MÓDULO DE REPORTES (Acceso Mixto - ¡CORREGIDO!)
    // ---------------------------------------------------
    // [CAMBIO] Usamos rutas directas en vez de 'mount' para evitar
    // problemas con la barra inclinada (/) al final de la URL.
    $router->get('/reportes', "$controllers\ReporteController@index");
    $router->post('/reportes/descargar-matriz', "$controllers\ReporteController@descargarMatriz");
    $router->post('/reportes/descargar-individual', "$controllers\ReporteController@descargarIndividual");
    $router->post('/reportes/descargar-matriz-pdf', "$controllers\ReporteController@descargarMatrizPdf");
    $router->post('/reportes/descargar-individual-pdf', "$controllers\ReporteController@descargarIndividualPdf");

    // ---------------------------------------------------
    // 3. ZONA ADMIN (SOLO ROL 'admin')
    // ---------------------------------------------------
    $router->mount('/admin', function () use ($router, $controllers) {

        // Middleware de rol
        $router->before('GET|POST', '/.*', function () {
            (new \App\Middleware\RoleMiddleware())->handle('admin');
        });

        // Home admin (opcional)
        $router->get('/', "$controllers\AdminController@index");

        // Gestión de Usuarios
        $router->get('/usuarios', "$controllers\AdminController@gestionarUsuarios");
        $router->post('/usuarios/guardar', "$controllers\AdminController@guardarUsuario");
        $router->get('/usuarios/eliminar/{id}', "$controllers\AdminController@eliminarUsuario");

        // Aprobaciones Pendientes
        $router->get('/pendientes', "$controllers\AdminController@listaPendientes");
        $router->get('/aprobar/{id}', "$controllers\AdminController@aprobarUsuario");
        $router->get('/rechazar/{id}', "$controllers\AdminController@rechazarUsuario");

        // Carga masiva
        $router->get('/carga-masiva', "$controllers\AdminController@vistaCargaMasiva");
        $router->post('/carga-masiva/procesar', "$controllers\AdminController@procesarCarga");
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