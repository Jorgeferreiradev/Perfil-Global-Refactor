<?php
/**
 * CRON JOB: Cierre y Apertura Automática de Semestre
 * Frecuencia recomendada: Ejecutar el 30 de Junio a las 23:59 y el 31 de Diciembre a las 23:59
 */

// 1. Cargar el entorno de tu aplicación
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/Database.php';

use Config\Database;

try {
    $pdo = Database::getInstance();
    $pdo->beginTransaction();

    // 2. Obtener el periodo activo actual
    $stmt = $pdo->query("SELECT * FROM periodos_academicos WHERE estado = 'activo' LIMIT 1");
    $periodoActual = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$periodoActual) {
        throw new Exception("No hay un periodo activo para cerrar.");
    }

    // 3. Cerrar el periodo actual
    $updateStmt = $pdo->prepare("UPDATE periodos_academicos SET estado = 'cerrado' WHERE id = :id");
    $updateStmt->execute([':id' => $periodoActual['id']]);

    // 4. Calcular el nombre y fechas del NUEVO periodo
    $añoActual = (int) substr($periodoActual['nombre_periodo'], 0, 4);
    $semestreActual = substr($periodoActual['nombre_periodo'], 5); // 'I' o 'II'

    if ($semestreActual === 'I') {
        $nuevoNombre = $añoActual . '-II';
        $nuevaFechaInicio = $añoActual . '-07-01';
        $nuevaFechaFin = $añoActual . '-12-31';
    } else {
        $nuevoAño = $añoActual + 1;
        $nuevoNombre = $nuevoAño . '-I';
        $nuevaFechaInicio = $nuevoAño . '-01-01';
        $nuevaFechaFin = $nuevoAño . '-06-30';
    }

    // 5. Insertar el nuevo periodo como ACTIVO
    $insertStmt = $pdo->prepare("
        INSERT INTO periodos_academicos (nombre_periodo, fecha_inicio, fecha_fin, estado) 
        VALUES (:nom, :fini, :ffin, 'activo')
    ");
    
    $insertStmt->execute([
        ':nom'  => $nuevoNombre,
        ':fini' => $nuevaFechaInicio,
        ':ffin' => $nuevaFechaFin
    ]);

    $pdo->commit();
    
    // Log de éxito (Se guarda en el servidor)
    echo "[" . date('Y-m-d H:i:s') . "] ÉXITO: Periodo {$periodoActual['nombre_periodo']} cerrado. Nuevo periodo {$nuevoNombre} abierto.\n";

} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    // Log de error
    echo "[" . date('Y-m-d H:i:s') . "] ERROR CRÍTICO: " . $e->getMessage() . "\n";
}