<?php
namespace App\Services;

class BackupService
{
    /**
     * Genera un respaldo físico (.sql) de la base de datos.
     * @param string $nombrePeriodo Ej: "2025_I"
     * @return string Nombre del archivo generado
     */
    public static function crearSnapshot(string $nombrePeriodo): string
    {
        $fecha = date('Y-m-d_H-i-s');
        $nombreArchivo = "backup_cierre_{$nombrePeriodo}_{$fecha}.sql";
        
        // Definimos la ruta donde se guardará
        $rutaDir = __DIR__ . "/../../storage/backups/";
        
        // Si no existe la carpeta, la creamos
        if (!is_dir($rutaDir)) {
            mkdir($rutaDir, 0777, true);
        }

        $rutaFinal = $rutaDir . $nombreArchivo;

        /**
         * COMANDO PARA MYSQLDUMP
         * Nota: En Windows/XAMPP, si no tienes mysqldump en el PATH, 
         * podrías necesitar poner la ruta completa como "C:/xampp/mysql/bin/mysqldump"
         */
        $comando = sprintf(
            'mysqldump -u %s -p%s %s > %s',
            escapeshellarg($_ENV['DB_USER']),
            escapeshellarg($_ENV['DB_PASS']),
            escapeshellarg($_ENV['DB_NAME']),
            escapeshellarg($rutaFinal)
        );

        // Ejecutar el comando del sistema
        exec($comando, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Error al generar el backup de la base de datos. Código: $returnVar");
        }

        return $nombreArchivo;
    }
}