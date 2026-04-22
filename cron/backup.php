<?php
// /cron/backup.php
// Este archivo NO debe ser accesible por navegador web, solo por el servidor (Cron Jobs)
// pendiente corregir conexion a base de datos, y programar backup cada semana en el servidor



require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../config');
$dotenv->safeLoad();

use App\Services\CorreoService;

// 1. Configuración de nombres
$dbName = $_ENV['DB_NAME'];
$dbUser = $_ENV['DB_USER'];
$dbPass = $_ENV['DB_PASS'];
$dbHost = $_ENV['DB_HOST'];

$fechaStr = date('Y-m-d_H-i-s');
$archivoSQL = __DIR__ . "/backup_{$dbName}_{$fechaStr}.sql";

// 2. Ejecutar mysqldump (Comando nativo de servidores Linux/Render)
// Usamos 2>&1 para capturar errores si los hay
if (empty($dbPass)) {
    $comando = "mysqldump -h {$dbHost} -u {$dbUser} {$dbName} > {$archivoSQL} 2>&1";
} else {
    $comando = "mysqldump -h {$dbHost} -u {$dbUser} -p'{$dbPass}' {$dbName} > {$archivoSQL} 2>&1";
}

exec($comando, $output, $resultCode);

if ($resultCode === 0 && file_exists($archivoSQL)) {
    // 3. Enviar por correo
    $mailer = new CorreoService();
    $enviado = $mailer->enviarBackupDB($archivoSQL);
    
    if ($enviado) {
        echo "✅ Backup generado y enviado por correo exitosamente.\n";
    } else {
        echo "⚠️ Backup generado, pero falló el envío por correo.\n";
    }

    // 4. Limpieza: Borrar el archivo local por seguridad para no llenar el servidor
    unlink($archivoSQL);
    
} else {
    echo "❌ Error al generar el backup. Código: {$resultCode}\n";
    print_r($output);
}