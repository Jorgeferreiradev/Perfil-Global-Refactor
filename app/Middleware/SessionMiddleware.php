<?php
namespace App\Middleware;

class SessionMiddleware {
    /**
     * Control de inactividad: cierra la sesión tras X segundos
     */
    public static function checkInactivity() {
        // Si no hay sesión, no hacemos nada
        if (!isset($_SESSION['user_id'])) {
            return;
        }

        // Definimos el tiempo (Ejemplo: 900 segundos = 15 minutos)
        $timeout = 900; 

        if (isset($_SESSION['last_activity'])) {
            $secondsInactive = time() - $_SESSION['last_activity'];

            if ($secondsInactive >= $timeout) {
                // Redirigir al logout con una bandera para avisar al usuario
                header('Location: ' . $_ENV['APP_URL'] . '/logout?reason=timeout');
                exit;
            }
        }

        // Actualizar el timestamp de actividad en cada clic
        $_SESSION['last_activity'] = time();
    }
}