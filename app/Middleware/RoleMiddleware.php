<?php
namespace App\Middleware;

class RoleMiddleware {
    /**
     * Verifica que el rol del usuario coincida con el requerido
     */
    public function handle($requiredRole) {
        // Asumimos que el rol se guarda en sesión al loguear
        $userRole = $_SESSION['user_rol'] ?? '';


        if ($userRole !== $requiredRole) {
            // Acceso prohibido
            header('HTTP/1.1 403 Forbidden');
            echo "403 - No tienes permiso para acceder a esta zona, comunicate con el administrador del sistema.";
            exit();
        }
    }
}