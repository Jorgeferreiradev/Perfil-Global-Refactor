<?php
namespace App\Controllers;

use App\Models\Reporte;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReporteController {

    public function index() {
        // Mostrar la vista de filtros
        $title = "Reportes y Métricas";
        $active = "reportes";
        
        // Aquí podrías cargar listas de programas para el select
        // $programaModel = new Programa();
        // $programas = $programaModel->getAll();

        require_once __DIR__ . '/../../views/layouts/header.php';
        require_once __DIR__ . '/../../views/layouts/sidebar.php';
        require_once __DIR__ . '/../../views/reports/index.php';
        require_once __DIR__ . '/../../views/layouts/footer.php';
    }

    public function descargarMatriz() {
        // 1. Validar Permisos (Solo Admin y Monitor)
        // (Esto ya debería estar cubierto por el Middleware, pero doble check no sobra)
        
        // 2. Recibir Filtros
        $inicio = $_POST['fecha_inicio'] ?? date('Y-01-01');
        $fin    = $_POST['fecha_fin'] ?? date('Y-12-31');
        
        // 3. Obtener Datos
        $reporteModel = new Reporte();
        $datos = $reporteModel->getMatrizSemestral($inicio, $fin);

        if (empty($datos)) {
            header('Location: /dashboard/reportes?error=sin_datos');
            exit;
        }

        // 4. Crear Excel con PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar Encabezados
        $headers = ['Documento', 'Estudiante', 'Programa', 'Evento', 'Línea Acción', 'Fecha Evento', 'Sede', 'Fecha Registro'];
        $sheet->fromArray([$headers], NULL, 'A1');

        // Estilos para el Encabezado (Negrita, Centrado, Fondo Gris)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4B5563']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Volcar los datos
        $row = 2;
        foreach ($datos as $d) {
            $sheet->setCellValue('A' . $row, $d['documento']);
            $sheet->setCellValue('B' . $row, $d['estudiante']);
            $sheet->setCellValue('C' . $row, $d['nombre_programa']);
            $sheet->setCellValue('D' . $row, $d['nombre_evento']);
            $sheet->setCellValue('E' . $row, $d['linea_accion']);
            $sheet->setCellValue('F' . $row, $d['fecha_inicio']);
            $sheet->setCellValue('G' . $row, $d['sede']);
            $sheet->setCellValue('H' . $row, $d['fecha_asistencia']);
            $row++;
        }

        // Autoajustar columnas
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // 5. Forzar descarga
        $filename = "Matriz_Semestral_" . date('Ymd_His') . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}