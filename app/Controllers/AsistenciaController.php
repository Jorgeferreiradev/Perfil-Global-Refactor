<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Asistencia;
use App\Models\Persona;

class AsistenciaController {

    // 1. Mostrar el formulario al estudiante tras escanear QR
    public function vistaRegistro($token) {
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token); // Debes crear este método en Evento Model

        if (!$evento) {
            die("Enlace inválido o expirado.");
        }

        // Vista simple para celular (Mobile First)
        require_once __DIR__ . '/../../views/public/registro_asistencia.php';
    }

    // 2. Procesar el documento ingresado
    public function registrar() {
        $token = $_POST['token'];
        $documento = $_POST['documento'];

        // a. Validar Evento
        $eventoModel = new Evento();
        $evento = $eventoModel->getByToken($token);

        // b. Buscar Estudiante (Persona)
        $personaModel = new Persona();
        $estudiante = $personaModel->getByDocumento($documento); // Debes crear este método

        if (!$estudiante) {
            // Manejo de error: Estudiante no existe en BD Maestra
            header("Location: /asistencia/$token?error=no_encontrado");
            exit;
        }

        // c. Registrar Asistencia
        $asistenciaModel = new Asistencia();
        
        // Evitar duplicados
        if ($asistenciaModel->yaRegistro($evento['id_evento'], $estudiante['id'])) {
            header("Location: /asistencia/$token?warning=ya_registrado");
            exit;
        }

        $asistenciaModel->create([
            'id_evento' => $evento['id_evento'],
            'persona_id' => $estudiante['id'],
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);

        header("Location: /asistencia/$token?success=registrado");
    }
}