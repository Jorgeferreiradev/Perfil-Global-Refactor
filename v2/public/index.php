<?php
#die("El index está recibiendo la ruta: " . $_GET['url']);
require_once __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config/');
$dotenv->load();

use App\Controllers\AuthController;
use App\Controllers\AdminController;

session_start();
// Capturamos la variable 'url' que viene del .htaccess
$url = $_GET['url'] ?? 'login';
$url = rtrim($url, '/');

$auth = new AuthController();

if ($url === 'login' || $url === '') {
    $auth->login();
} elseif ($url === 'autenticar') {
    $auth->autenticar();
} elseif ($url === 'admin/dashboard') {
    $admin = new AdminController();
    $admin->dashboard();
    } elseif ($url === 'admin/importar-usuarios') { // <--- ESTA ES LA RUTA importar-usuarios
    (new AdminController())->importarUsuarios();
} else {
    echo "404 - Ruta no encontrada: " . $url;
}