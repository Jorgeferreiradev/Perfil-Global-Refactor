<?php
namespace App\Services;

use Config\Database;
use App\Services\BackupService;

class SemesterService {
    /**
     * Revisa la fecha actual contra la fecha_fin del periodo activo
     */
    public static function verificarCierreAutomatico() {
        $db = Database::getInstance();
        
        // 1. Buscar periodo activo que ya venció
        $sql = "SELECT id, nombre_periodo FROM periodos_academicos 
                WHERE estado = 'activo' AND fecha_fin < CURDATE() LIMIT 1";
        $stmt = $db->query($sql);
        $periodo = $stmt->fetch();

        if ($periodo) {
            try {
                // 2. Snapshot Obligatorio (Usa tu BackupService)
                BackupService::crearSnapshot($periodo['nombre_periodo']);
                
                // 3. Bloqueo Lógico: Cambiar a 'cerrado'
                $update = $db->prepare("UPDATE periodos_academicos SET estado = 'cerrado' WHERE id = ?");
                $update->execute([$periodo['id']]);
                
                // 4. Log del sistema
                $log = $db->prepare("INSERT INTO logs_sistema (usuario_id, accion) VALUES (NULL, ?)");
                $log->execute(["CIERRE AUTOMÁTICO: Periodo " . $periodo['nombre_periodo'] . " cerrado por fecha."]);

            } catch (\Exception $e) {
                error_log("CRÍTICO: Falló el cierre automático: " . $e->getMessage());
            }
        }
    }
}