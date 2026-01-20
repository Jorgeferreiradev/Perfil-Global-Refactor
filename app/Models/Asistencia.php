<?php
namespace App\Models;

use Config\Database;
use PDO;

class Asistencia {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance();
    }

    // Registrar la asistencia
    public function registrar($data) {
        $sql = "INSERT INTO asistencias (id_evento, persona_id, id_periodo, ip_registro, es_simulacion) 
                VALUES (:evt, :pers, :per, :ip, 0)";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':evt'  => $data['id_evento'],
            ':pers' => $data['persona_id'],
            ':per'  => $data['id_periodo'],
            ':ip'   => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
        ]);

        return $this->pdo->lastInsertId();
    }

    // Verificar si ya se registró hoy a este evento
    public function yaRegistrado($idEvento, $idPersona) {
        $sql = "SELECT id FROM asistencias WHERE id_evento = :evt AND persona_id = :pers LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':evt' => $idEvento, ':pers' => $idPersona]);
        return $stmt->fetch();
    }

    // Obtener lista de asistentes con sus datos personales y programa
public function getByEvento($idEvento) {
    // JOIN corregido: Asistencia -> Persona -> Historial -> Programa
    $sql = "SELECT 
                a.fecha_asistencia, 
                p.numero_documento, 
                p.nombres, 
                p.apellidos, 
                prog.nombre_programa,  
                a.ip_registro
            FROM asistencias a
            INNER JOIN personas p ON a.persona_id = p.id
            -- El truco: Unimos con el historial para saber el programa
            LEFT JOIN historial_academico h ON p.id = h.persona_id
            LEFT JOIN programas prog ON h.id_programa = prog.id_programa
            WHERE a.id_evento = :evt
            ORDER BY a.fecha_asistencia DESC";
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':evt' => $idEvento]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}