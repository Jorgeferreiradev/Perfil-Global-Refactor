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
    $user = $usuarioModel->getByCorreo($email);

    if ($user && password_verify($password, $user['password'])) {
        // 1. Guardar sesión básica
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombres'] . ' ' . $user['apellidos'];
        $_SESSION['user_rol'] = $user['rol']; // Solo será 'admin' o 'monitor'
        $_SESSION['last_activity'] = time();

        // 2. Redirigir al Dashboard Único
        header('Location: ' . BASE_URL . '/dashboard');
        exit;
    } else {
        $_SESSION['error'] = 'Credenciales inválidas';
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
}

// En App/Controllers/AuthController.php

/* ========================================================
       MÉTODOS DE RECUPERACIÓN DE CONTRASEÑA (AGREGA ESTO)
    ======================================================== */

    // 1. Mostrar el formulario (Corrige la pantalla blanca)
    public function showForgotPassword() {
        // Verifica que la ruta del archivo sea EXACTAMENTE esta
        $viewPath = __DIR__ . '/../../resources/views/auth/forgot-password.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            // Esto te dirá si el archivo está mal ubicado
            die("Error: No encuentro la vista en: " . $viewPath);
        }
    }

    // 2. Procesar el envío del correo (Ruta /auth/recovery)
    public function sendRecoveryLink() {
        // Validar que llegó el correo
        $email = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
        
        if (!$email) {
            $_SESSION['error'] = 'Por favor escribe un correo válido.';
            header('Location: ' . trim(BASE_URL) . '/auth/forgot-password');
            exit;
        }

        $usuarioModel = new \App\Models\Usuario();
        $user = $usuarioModel->getByCorreo($email);

        if ($user) {
            // Generar Token
            $token = bin2hex(random_bytes(20));
            $expires = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Guardar en BD (Asegúrate de haber agregado los métodos al modelo Usuario)
            $usuarioModel->saveResetToken($user['id'], $token, $expires);

            // Enviar Correo (Requiere App\Services\CorreoService)
            // Si no tienes el servicio de correo aún, comenta estas 3 líneas para que no falle
            try {
                $mailer = new \App\Services\CorreoService(); 
                $link = trim(BASE_URL) . "/auth/reset-password/" . $token;
                $mailer->enviarCredenciales($email, $user['nombres'], "Tu link de recuperación es: <a href='$link'>$link</a>");
            } catch (\Exception $e) {
                // Si falla el correo, no rompemos la app, solo avisamos
                error_log("Error enviando correo: " . $e->getMessage());
            }
        }

        // Siempre decimos que se envió por seguridad (para no revelar qué correos existen)
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Si el correo existe, recibirás un enlace de recuperación.'];
        header('Location: ' . trim(BASE_URL) . '/login');
        exit;
    }

    // 3. Mostrar formulario de Nueva Contraseña (Ruta /auth/reset-password/{token})
    public function showResetPassword($token) {
        $usuarioModel = new \App\Models\Usuario();
        $user = $usuarioModel->getByToken($token);

        // Validar si el token existe y no ha expirado
        if (!$user || strtotime($user['reset_expires']) < time()) {
            $_SESSION['error'] = 'El enlace es inválido o ha expirado.';
            header('Location: ' . trim(BASE_URL) . '/login');
            exit;
        }

        // Cargar la vista de cambio de clave
        require_once __DIR__ . '/../../resources/views/auth/reset-password.php';
    }

    // 4. Guardar la nueva contraseña (Ruta /auth/update-password)
    public function updatePassword() {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (empty($token) || empty($password)) {
            $_SESSION['error'] = 'Todos los datos son obligatorios.';
            header('Location: ' . trim(BASE_URL) . '/login');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            // Aquí idealmente volveríamos al form del token, pero por simpleza vamos al login
            header('Location: ' . trim(BASE_URL) . '/login'); 
            exit;
        }

        $usuarioModel = new \App\Models\Usuario();
        $user = $usuarioModel->getByToken($token);

        if ($user) {
            // Actualizar password (el método updatePerfil ya hace el hash)
            $usuarioModel->updatePerfil($user['id'], $user['nombres'], $user['apellidos'], $password);
            
            // Borrar el token para que no se use de nuevo
            $usuarioModel->clearResetToken($user['id']);
            
            $_SESSION['flash'] = ['type' => 'success', 'msg' => '¡Contraseña actualizada! Inicia sesión.'];
        } else {
            $_SESSION['error'] = 'Token inválido.';
        }

        header('Location: ' . trim(BASE_URL) . '/login');
        exit;
    }

    public function logout() {
        // 3. Destruir sesión   
        session_unset();
        session_destroy();
        
        // 4. CAMBIO: Al salir, ir al login correcto
        header('Location: ' . BASE_URL . '/login'); // <--- CAMBIO AQUÍ
        exit;
    }
}