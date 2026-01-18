<?php
namespace App\Middleware;

class SessionMiddleware {
    public function handle() {
        // Si no existe la sesión del ID de usuario
        if (!isset($_SESSION['user_id'])) {
            // Detener todo y mandar al login usando la constante BASE_URL
            header('Location: ' . BASE_URL . '/login');
            exit(); // ¡CRUCIAL! Sin el exit, el código sigue ejecutándose
        }

        // Opcional: Verificar inactividad (30 min)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            session_unset();
            session_destroy();
            header('Location: ' . BASE_URL . '/login?timeout=1');
            exit();
        }
        $_SESSION['last_activity'] = time();
    }
}