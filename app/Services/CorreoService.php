<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class CorreoService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        
        // --- CONFIGURACIÓN SMTP ---
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com'; // Servidor de Gmail
        $this->mail->SMTPAuth   = true;
        // 🔥 IMPORTANTE: Usa variables de entorno (.env) o config.php para esto
        $this->mail->Username   = 'perfilglobal.fesc@gmail.com'; 
        $this->mail->Password   = 'xxif rvcs bkvl vhle'; // No es tu clave normal
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;
        
        $this->mail->setFrom('no-reply@perfilglobal.com', 'Perfil Global V2');
        $this->mail->CharSet = 'UTF-8';
    }

    public function enviarBienvenidaUsuario($email, $nombre, $password) {
    try {
        $this->mail->addAddress($email, $nombre);
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Bienvenido al Sistema - Perfil Global V2';
        
        $this->mail->Body = "
            <div style='font-family: sans-serif; border: 1px solid #eee; padding: 20px; border-radius: 10px;'>
                <h2 style='color: #0d6efd;'>¡Hola, {$nombre}!</h2>
                <p>Se ha creado una cuenta para ti en la plataforma <strong>Perfil Global V2</strong>.</p>
                <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p style='margin: 0;'><strong>Usuario:</strong> {$email}</p>
                    <p style='margin: 0;'><strong>Contraseña temporal:</strong> <span style='color: #d63384;'>{$password}</span></p>
                </div>
                <p>Te recomendamos cambiar tu contraseña al ingresar por primera vez.</p>
                <a href='" . BASE_URL . "/login' style='background: #0d6efd; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Acceder ahora</a>
            </div>";
        
        return $this->mail->send();
    } catch (Exception $e) {
        return false;
    }
}

    public function enviarCredenciales($email, $nombre, $mensajeHtml) {
        try {
            $this->mail->addAddress($email, $nombre);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Recuperación de Contraseña - Perfil Global';
            $this->mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 20px;'>
                    <h2 style='color: #0d6efd;'>Hola, $nombre</h2>
                    <p>Has solicitado restablecer tu contraseña en PerfilGlobal.</p>
                    <div style='background: #f8f9fa; padding: 15px; border-radius: 5px; text-align: center;'>
                        $mensajeHtml
                    </div>
                    <p style='font-size: 0.8em; color: #777; margin-top: 20px;'>
                        Si no solicitaste este cambio, puedes ignorar este correo de forma segura.
                    </p>
                </div>";

            return $this->mail->send();
        } catch (Exception $e) {
            error_log("Error de correo: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}