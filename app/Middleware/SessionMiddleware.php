<?php
namespace App\Middleware;

class SessionMiddleware {
    public function handle() {
        // Si no existe la variable de sesión 'user_id', redirigir
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }
        
        // BONUS: Implementación del HU-D03 (Limpieza sesión DEV)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            // 30 min inactividad
            session_unset();
            session_destroy();
            header('Location: /login?timeout=1');
            exit();
        }
        $_SESSION['last_activity'] = time();
    }
}