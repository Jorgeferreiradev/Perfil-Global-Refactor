<?php
namespace App\Middleware;

use Config\Database;

class RoleMiddleware {
    // Verifica quién puede entrar a qué carpeta
    public static function check(array $rolesPermitidos) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . $_ENV['APP_URL'] . '/login');
            exit;
        }

        $rol = $_SESSION['rol'];
        if ($rol === 'dev') return true; // El DEV entra a todo

        if (!in_array($rol, $rolesPermitidos)) {
            $_SESSION['error'] = "Acceso denegado a esta función.";
            $destino = ($rol === 'admin') ? '/admin/dashboard' : '/monitor/dashboard';
            header('Location: ' . $_ENV['APP_URL'] . $destino);
            exit;
        }
    }

    // Verifica si el periodo permite edición (Solo lectura)
    public static function isPeriodoActivo($idPeriodo) {
        if ($_SESSION['rol'] === 'dev') return true; // DEV puede probar en cerrados

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT estado FROM periodos_academicos WHERE id = ?");
        $stmt->execute([$idPeriodo]);
        $periodo = $stmt->fetch();

        if ($periodo && $periodo['estado'] === 'cerrado') {
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                $_SESSION['error'] = "El periodo está CERRADO. No se permiten cambios.";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }
        }
    }
}