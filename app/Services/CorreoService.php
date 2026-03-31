<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreoService {
    
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        try {
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com'; 
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'tu_correo@gmail.com'; // ⚠️ PON TU GMAIL AQUÍ
            $this->mail->Password   = 'las16letrasdegoogle'; // ⚠️ PON LA CLAVE DE 16 LETRAS SIN ESPACIOS
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port       = 587;
            
            $this->mail->setFrom('tu_correo@gmail.com', 'Soporte Perfil Global');
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
        } catch (Exception $e) {
            // Silencioso en constructor
        }
    }

    public function enviarCredenciales($correo, $nombre, $password) {
        try {
            $this->mail->addAddress($correo, $nombre);
            $this->mail->Subject = 'Bienvenido a Perfil Global V2 - Credenciales';
            $this->mail->Body = "
                <h1>¡Hola, $nombre!</h1>
                <p>Se ha creado tu cuenta en el sistema Perfil Global.</p>
                <ul>
                    <li>Usuario: $correo</li>
                    <li>Contraseña: <strong>$password</strong></li>
                </ul>
                <a href='" . BASE_URL . "/login'>Iniciar Sesión</a>
            ";
            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }

    // 🔥 NUEVO: Método para recuperación de contraseñas
    public function enviarRecuperacion($correoDestino, $nombre, $token) {
        try {
            $this->mail->addAddress($correoDestino, $nombre);
            $this->mail->Subject = 'Recuperacion de Contraseña - Perfil Global';
            
            $enlace = BASE_URL . "/auth/reset-password/" . $token;

            $this->mail->Body = "
                <h2>Hola, {$nombre}</h2>
                <p>Has solicitado recuperar tu contraseña. Haz clic en el enlace para crear una nueva (Expira en 1 hora):</p>
                <a href='{$enlace}' style='padding: 10px 15px; background: #dc3545; color: #fff; text-decoration: none;'>Restablecer Contraseña</a>
            ";
            return $this->mail->send();
        } catch (Exception $e) {
            return false;
        }
    }
}