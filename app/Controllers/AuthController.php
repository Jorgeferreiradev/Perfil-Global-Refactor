<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController {
    
    public function showLogin() {
        // Si ya está logueado, enviar al dashboard
        if (isset($_SESSION['user_id'])) {
            // 1. CAMBIO: Usamos BASE_URL para redirigir bien
            header('Location: ' . BASE_URL . '/dashboard'); // <--- CAMBIO AQUÍ
            exit;
        }
        require_once __DIR__ . '/../../resources/views/auth/login.php';
    }

    public function login() {
        $email = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $usuarioModel = new Usuario();
        $user = $usuarioModel->findByEmail($email);

        // Verificar usuario y contraseña (hash)
        if ($user && password_verify($password, $user['password'])) {
            // Login Exitoso: Guardar datos en sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nombre'] = $user['nombres'] . ' ' . $user['apellidos'];
            $_SESSION['user_rol'] = $user['rol']; 
            $_SESSION['last_activity'] = time();

            // Configurar Sandbox si es DEV
            if ($user['rol'] === 'dev') {
                $_SESSION['is_sandbox'] = true;
            }

            // 2. CAMBIO: Esta es la línea que te estaba fallando al ingresar
            header('Location: ' . BASE_URL . '/dashboard'); // <--- CAMBIO AQUÍ
            exit;
        } else {
            // Error
            $_SESSION['error'] = 'Credenciales inválidas';
            // 3. CAMBIO: Si falla, volver al login correcto
            header('Location: ' . BASE_URL . '/login'); // <--- CAMBIO AQUÍ
            exit;
        }
    }

    public function logout() {
        // HU-D02: Limpieza automática si es DEV
        if (isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'dev') {
            $model = new Usuario();
            $model->cleanSimulationData();
        }

        session_unset();
        session_destroy();
        
        // 4. CAMBIO: Al salir, ir al login correcto
        header('Location: ' . BASE_URL . '/login'); // <--- CAMBIO AQUÍ
        exit;
    }
}