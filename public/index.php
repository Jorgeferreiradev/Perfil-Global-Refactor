<?php
// --- MODO DEBUG: ACTIVADO (Apagar en producción) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ---------------------------------------------------

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

$router->setBasePath('/perfilglobal_v2/public');

// Namespaces
$controllers = 'App\Controllers';
$middleware  = 'App\Middleware';

// =======================================================
// A. RUTAS PÚBLICAS (SIN LOGIN - ACCESO LIBRE)
// =======================================================

// 1. Autenticación Básica
$router->get('/', "$controllers\AuthController@showLogin");
$router->get('/login', "$controllers\AuthController@showLogin");
$router->post('/auth/login', "$controllers\AuthController@login");
$router->get('/logout', "$controllers\AuthController@logout");

// 2. Recuperación de Contraseña (Flujo Completo)
// A. Pedir correo ("Olvidé mi contraseña")
$router->get('/auth/forgot-password', "$controllers\AuthController@showForgotPassword");
// B. Procesar envío del link al correo
$router->post('/auth/recovery', "$controllers\AuthController@sendRecoveryLink");
// C. Clic en el link del correo (Validar Token)
$router->get('/auth/reset-password/(\w+)', "$controllers\AuthController@showResetPassword");
// D. Guardar la nueva contraseña
$router->post('/auth/update-password', "$controllers\AuthController@updatePassword");

// 3. Asistencia Pública
// A. Rutas Específicas
$router->get('/asistencia/nuevo/{token}/{documento}', "$controllers\AsistenciaController@vistaRegistroManual");
$router->post('/asistencia/guardar-manual', "$controllers\AsistenciaController@guardarManual");
$router->post('/asistencia/registrar', "$controllers\AsistenciaController@registrar");

// B. Ruta Genérica (Token)
$router->get('/asistencia/{token}', "$controllers\AsistenciaController@vistaRegistro");


// =======================================================
// B. RUTAS PROTEGIDAS (DASHBOARD)
// =======================================================
$router->mount('/dashboard', function () use ($router, $controllers, $middleware) {

    // Middleware de sesión (Nadie pasa si no está logueado)
    $router->before('GET|POST', '/.*', "$middleware\SessionMiddleware@handle");

    // Dashboard principal
    $router->get('/', "$controllers\DashboardController@index");

    // 1. MÓDULO DE EVENTOS
    $router->mount('/eventos', function () use ($router, $controllers) {
        $router->get('/', "$controllers\EventoController@index");
        $router->post('/crear', "$controllers\EventoController@store");
        $router->get('/qr/{token}', "$controllers\EventoController@mostrarQR");
        $router->get('/asistentes/{id}', "$controllers\EventoController@verAsistentes");
        $router->post('/editar/{id}', "$controllers\EventoController@update");
        $router->get('/estado/{id}/{estado}', "$controllers\EventoController@cambiarEstado");
    });

    // 2. MÓDULO DE REPORTES
    $router->get('/reportes', "$controllers\ReporteController@index");
    $router->post('/reportes/descargar-matriz', "$controllers\ReporteController@descargarMatriz");
    $router->post('/reportes/descargar-individual', "$controllers\ReporteController@descargarIndividual");
    $router->post('/reportes/descargar-matriz-pdf', "$controllers\ReporteController@descargarMatrizPdf");
    $router->post('/reportes/descargar-individual-pdf', "$controllers\ReporteController@descargarIndividualPdf");

    // 3. MÓDULO PERSONAS
    $router->mount('/personas', function () use ($router, $controllers) {
        $router->get('/', "$controllers\PersonasController@index");
        $router->get('/crear', "$controllers\PersonasController@create");
        $router->post('/guardar', "$controllers\PersonasController@store");
        $router->get('/editar/{id}', "$controllers\PersonasController@edit");
        $router->post('/actualizar/{id}', "$controllers\PersonasController@update");
        $router->get('/eliminar/{id}', "$controllers\PersonasController@delete");
    });

    // 4. ZONA ADMIN (SOLO ROL 'admin')
    $router->mount('/admin', function () use ($router, $controllers) {
        
        // Middleware de rol
        $router->before('GET|POST', '/.*', function () {
            (new \App\Middleware\RoleMiddleware())->handle('admin');
        });

        // Home admin
        $router->get('/', "$controllers\AdminController@index");

        // Gestión de Usuarios
        $router->get('/usuarios', "$controllers\AdminController@gestionarUsuarios");
        $router->post('/usuarios/guardar', "$controllers\AdminController@guardarUsuario");
        $router->get('/usuarios/editar/{id}', "$controllers\AdminController@editarUsuario");
        $router->post('/usuarios/actualizar/{id}', "$controllers\AdminController@actualizarUsuario");
        
        // ACTIVAR / DESACTIVAR USUARIO (La ruta que te fallaba)
        $router->get('/usuarios/estado/{id}', "$controllers\AdminController@cambiarEstadoUsuario");

        // Aprobaciones Pendientes
        $router->get('/pendientes', "$controllers\AdminController@listaPendientes");
        $router->get('/aprobar/{id}', "$controllers\AdminController@aprobarUsuario");
        $router->get('/rechazar/{id}', "$controllers\AdminController@rechazarUsuario");

        // Carga masiva
        $router->get('/carga-masiva', "$controllers\AdminController@vistaCargaMasiva");
        $router->post('/carga-masiva/procesar', "$controllers\AdminController@procesarCarga");
    });

}); // Fin del mount /dashboard


// =======================================================
// C. MÓDULO MI PERFIL (Ruta Protegida Independiente)
// =======================================================
// Lo dejamos fuera del dashboard por si quieres acceder directo a /perfil
$router->mount('/perfil', function () use ($router, $controllers, $middleware) {
    
    // Proteger la ruta (Importante)
    $router->before('GET|POST', '/.*', "$middleware\SessionMiddleware@handle");

    $router->get('/', "$controllers\PerfilController@index");
    $router->post('/actualizar', "$controllers\PerfilController@update");
});


// =======================================================
// ERROR 404
// =======================================================
$router->set404(function () {
    header('HTTP/1.1 404 Not Found');
    echo '<h1>404 - Página no encontrada </h1><p>Verifica la URL ingresada.</p>';
});

// Ejecutar Router (SIN BARRA INVERTIDA AL FINAL)
$router->run();