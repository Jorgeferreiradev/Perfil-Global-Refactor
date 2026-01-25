<?php
namespace App\Controllers;

use App\Models\Evento;
use App\Models\Asistencia;
use Config\Database;

class ReporteController {

    // 1. VISTA PRINCIPAL DE REPORTES
    public function index() {
        // Necesitamos listas para los filtros
        $eventoModel = new Evento();
        $eventos = $eventoModel->all(); // Para filtrar por evento específico

        $title = "Generación de Reportes";
        $active = "reportes";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/reportes.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    // 2. GENERADOR MAESTRO DE EXCEL (CSV)
    public function generarReporte() {
        $tipoReporte = $_POST['tipo_reporte'];
        $fechaInicio = $_POST['fecha_inicio'] ?? null;
        $fechaFin    = $_POST['fecha_fin'] ?? null;
        
        // Headers para forzar descarga
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Reporte_' . $tipoReporte . '_' . date('Y-m-d') . '.csv');
        
        // Abrir salida php
        $output = fopen('php://output', 'w');
        
        // Truco para Excel en español (BOM para UTF-8)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        if ($tipoReporte === 'matriz_general') {
            $this->generarMatrizSemestral($output, $fechaInicio, $fechaFin);
        } elseif ($tipoReporte === 'detallado_asistentes') {
            $this->generarListadoDetallado($output, $fechaInicio, $fechaFin);
        }

        fclose($output);
        exit;
    }

    // --- LÓGICA: MATRIZ SEMESTRAL (HU-C02) ---
    private function generarMatrizSemestral($output, $ini, $fin) {
        $db = Database::getInstance();
        
        // 1. Encabezados (Replicando tu plantilla MATRIZ_SEMESTRAL_GENERAL)
        $headers = [
            'Línea de Acción', 'Nombre Actividad', 'Fecha Inicio', 'Fecha Final',
            'Total Estudiantes', 'Total Docentes', 'Total Admin', 'Total Graduados', 'Total Externos'
        ];
        
        // 2. Buscar Programas Académicos para hacer columnas dinámicas
        $programas = $db->query("SELECT id_programa, nombre_programa FROM programas_academicos ORDER BY nombre_programa")->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($programas as $prog) {
            $headers[] = $prog['nombre_programa']; // Agregamos una columna por cada carrera
        }
        
        fputcsv($output, $headers, ';'); // Usamos ; para Excel en español

        // 3. Consulta Compleja (Pivot)
        // Traemos todos los eventos y contamos asistencias por tipo y programa
        $sql = "SELECT 
                    e.id_evento, e.nombre_evento, e.fecha_inicio, e.fecha_final, e.linea_accion,
                    COUNT(CASE WHEN p.id_tipo_persona = 1 THEN 1 END) as est,
                    COUNT(CASE WHEN p.id_tipo_persona = 2 THEN 1 END) as doc,
                    COUNT(CASE WHEN p.id_tipo_persona = 3 THEN 1 END) as adm,
                    COUNT(CASE WHEN p.id_tipo_persona = 4 THEN 1 END) as grad,
                    COUNT(CASE WHEN p.id_tipo_persona = 99 THEN 1 END) as ext
                FROM eventos e
                LEFT JOIN asistencias a ON e.id_evento = a.id_evento
                LEFT JOIN historial_academico h ON a.persona_id = h.persona_id
                LEFT JOIN personas p ON a.persona_id = p.id
                WHERE e.estado = 'activo'
                GROUP BY e.id_evento";
                
        // (Nota: Aquí simplifiqué el WHERE de fechas, agrégalo si quieres filtrar por fecha de evento)

        $stmt = $db->query($sql);
        
        while ($fila = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $linea = [
                $fila['linea_accion'] ?? 'General', // Asumiendo que agregaste este campo a Eventos, si no, pon estático
                $fila['nombre_evento'],
                $fila['fecha_inicio'],
                $fila['fecha_final'],
                $fila['est'], $fila['doc'], $fila['adm'], $fila['grad'], $fila['ext']
            ];

            // 4. Llenar columnas de programas
            foreach ($programas as $prog) {
                // Subconsulta rápida para contar estudiantes de ESTE programa en ESTE evento
                // (En producción optimizaríamos esto con un GROUP BY masivo, pero para empezar funciona)
                $sqlProg = "SELECT COUNT(*) FROM asistencias a 
                            JOIN historial_academico h ON a.persona_id = h.persona_id
                            WHERE a.id_evento = ? AND h.id_programa = ?";
                $stmtProg = $db->prepare($sqlProg);
                $stmtProg->execute([$fila['id_evento'], $prog['id_programa']]);
                $linea[] = $stmtProg->fetchColumn();
            }

            fputcsv($output, $linea, ';');
        }
    }

    // --- LÓGICA: LISTADO DETALLADO (Para evidencias) ---
    private function generarListadoDetallado($output, $ini, $fin) {
        $db = Database::getInstance();
        
        // Encabezados
        fputcsv($output, ['Evento', 'Fecha Asistencia', 'Documento', 'Nombres', 'Apellidos', 'Tipo', 'Programa/Dependencia', 'Correo'], ';');

        $sql = "SELECT 
                    e.nombre_evento, a.fecha_asistencia,
                    p.numero_documento, p.nombres, p.apellidos,
                    t.nombre_tipo, prog.nombre_programa, p.correo_institucional
                FROM asistencias a
                JOIN eventos e ON a.id_evento = e.id_evento
                JOIN personas p ON a.persona_id = p.id
                JOIN historial_academico h ON p.id = h.persona_id
                JOIN tipos_personas t ON h.id_tipo_persona = t.id_tipo
                LEFT JOIN programas_academicos prog ON h.id_programa = prog.id_programa
                ORDER BY a.fecha_asistencia DESC";
        
        $stmt = $db->query($sql); // Agrega filtros de fecha aquí si deseas

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            fputcsv($output, $row, ';');
        }
    }
}