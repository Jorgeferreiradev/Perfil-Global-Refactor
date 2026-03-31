<?php
namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelReportService {

    private $spreadsheet;
    private $sheet;
    private $currentRow;

    public function __construct() {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();
        $this->currentRow = 1;
    }

    // ==========================================
    // 📊 REPORTE 1: MATRIZ GENERAL (MAGISTRAL)
    // ==========================================
    public function generarMatrizSemestral($semestre, $datosPorLinea, $sede = 'Todas') {
        $sheet = $this->sheet;
        $sheet->setTitle("Matriz $semestre");

        // 1. TÍTULO GENERAL
        $sheet->mergeCells("A1:I1");
        $sheet->setCellValue('A1', "MATRIZ GENERAL DE BIENESTAR INSTITUCIONAL - SEMESTRE $semestre");
        $sheet->setCellValue('A2', 'Semestre: ' . $semestre . ' | Sede: ' . strtoupper($sede));
        // (Opcional) Ponerle negrita a la celda A2 para que destaque
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $this->estilarTitulo('A1');
        $this->currentRow = 3;

        foreach ($datosPorLinea as $nombreLinea => $data) {
            // 2. ENCABEZADO DE LÍNEA DE ACCIÓN (Barra Azul Oscura)
            $sheet->mergeCells("A{$this->currentRow}:I{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "LÍNEA DE ACCIÓN: " . strtoupper($nombreLinea));
            $this->estilarSubtitulo("A{$this->currentRow}");
            $this->currentRow++;

            // 3. ENCABEZADOS DE TABLA
            $headers = ['Nombre Actividad', 'Inicio', 'Fin', 'Estudiantes', 'Docentes', 'Admin', 'Graduados', 'Externos', 'TOTAL'];
            $col = 'A';
            foreach ($headers as $h) {
                $sheet->setCellValue("$col{$this->currentRow}", $h);
                $this->estilarCabecera("$col{$this->currentRow}");
                $col++;
            }
            $this->currentRow++;

            // 4. DATOS DE LOS EVENTOS
            $inicioEventos = $this->currentRow;
            foreach ($data['eventos'] as $ev) {
                $sheet->setCellValue("A{$this->currentRow}", $ev['nombre_evento']);
                $sheet->setCellValue("B{$this->currentRow}", $ev['fecha_inicio']);
                $sheet->setCellValue("C{$this->currentRow}", $ev['fecha_final']);
                $sheet->setCellValue("D{$this->currentRow}", $ev['est']);
                $sheet->setCellValue("E{$this->currentRow}", $ev['doc']);
                $sheet->setCellValue("F{$this->currentRow}", $ev['adm']);
                $sheet->setCellValue("G{$this->currentRow}", $ev['grad']);
                $sheet->setCellValue("H{$this->currentRow}", $ev['ext']);
                $sheet->setCellValue("I{$this->currentRow}", $ev['total_bruto']);
                
                // Estilo suave para filas
                $sheet->getStyle("A{$this->currentRow}:I{$this->currentRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                $this->currentRow++;
            }

            // 5. TOTALES BENEFICIARIOS REALES (Destacado en Amarillo)
            $this->currentRow++; // Espacio
            $sheet->mergeCells("A{$this->currentRow}:C{$this->currentRow}");
            $sheet->setCellValue("A{$this->currentRow}", "TOTAL BENEFICIARIOS REALES (Sin Duplicados)");
            
            $reales = $data['reales'];
            $sheet->setCellValue("D{$this->currentRow}", $reales['real_est']);
            $sheet->setCellValue("E{$this->currentRow}", $reales['real_doc']);
            $sheet->setCellValue("F{$this->currentRow}", $reales['real_adm']);
            $sheet->setCellValue("G{$this->currentRow}", $reales['real_grad']);
            $sheet->setCellValue("H{$this->currentRow}", $reales['real_ext']);
            $sheet->setCellValue("I{$this->currentRow}", array_sum($reales));

            $this->estilarTotales("A{$this->currentRow}:I{$this->currentRow}");
            $this->currentRow += 3; // Espacio entre líneas
        }

        // AUTO AJUSTAR COLUMNAS (Para que no se vea amontonado)
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $this->descargarArchivo("Matriz_General_$semestre");
    }

    // ==========================================
    // 📄 REPORTE 2: INDIVIDUAL (MAGISTRAL)
    // ==========================================
    public function generarIndividual($info, $resumen, $asistentes) {
        $sheet = $this->sheet;
        $nombreLimpio = substr(preg_replace('/[^A-Za-z0-9]/', '', $info['nombre_evento']), 0, 20);
        $sheet->setTitle($nombreLimpio);

        // 1. FICHA TÉCNICA
        $sheet->mergeCells("A1:E1");
        $sheet->setCellValue('A1', "REPORTE DE ACTIVIDAD");
        $this->estilarTitulo('A1');

        $datosFicha = [
            'Línea de Acción' => $info['linea_accion'],
            'Actividad'       => $info['nombre_evento'],
            'Fechas'          => $info['fecha_inicio'] . ' al ' . $info['fecha_final'],
            'Total Asistentes'=> count($asistentes)
        ];

        $row = 3;
        foreach ($datosFicha as $key => $val) {
            $sheet->setCellValue("A$row", $key);
            $sheet->mergeCells("B$row:E$row");
            $sheet->setCellValue("B$row", $val);
            $sheet->getStyle("A$row")->getFont()->setBold(true);
            $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
            $row++;
        }

        // 2. RESUMEN
        $row += 2;
        $sheet->mergeCells("A$row:E$row");
        $sheet->setCellValue("A$row", "RESUMEN DE POBLACIÓN");
        $this->estilarSubtitulo("A$row");
        $row++;

        $sheet->fromArray(['Estudiantes', 'Docentes', 'Admin', 'Graduados', 'Externos'], NULL, "A$row");
        $this->estilarCabecera("A$row:E$row");
        $row++;
        
        $sheet->fromArray([
            $resumen['Estudiante'], $resumen['Docente'], $resumen['Administrativo'], 
            $resumen['Egresado'], $resumen['Invitado']
        ], NULL, "A$row");
        $sheet->getStyle("A$row:E$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 3. LISTADO DETALLADO
        $row += 3;
        $sheet->mergeCells("A$row:E$row");
        $sheet->setCellValue("A$row", "LISTADO DE ASISTENCIA");
        $this->estilarSubtitulo("A$row");
        $row++;

        $sheet->fromArray(['Documento', 'Nombre Completo', 'Vinculación', 'Programa / Dependencia', 'Fecha'], NULL, "A$row");
        $this->estilarCabecera("A$row:E$row");
        $row++;

        // Insertar datos masivos
        foreach ($asistentes as $p) {
            $sheet->setCellValue("A$row", $p['numero_documento']);
            $sheet->setCellValue("B$row", $p['nombre_completo']);
            $sheet->setCellValue("C$row", $p['tipo_vinculacion']);
            $sheet->setCellValue("D$row", $p['programa_dependencia']);
            $sheet->setCellValue("E$row", $p['fecha_asistencia']);
            $row++;
        }

        // Ajustar anchos
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $this->descargarArchivo("Reporte_$nombreLimpio");
    }

    // ==========================================
    // 🎨 ESTILOS PRIVADOS (HELPER)
    // ==========================================
    private function estilarTitulo($rango) {
        $style = $this->sheet->getStyle($rango);
        $style->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF0D6EFD'); // Azul Bootstrap
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function estilarSubtitulo($rango) {
        $style = $this->sheet->getStyle($rango);
        $style->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFFFF'));
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF6C757D'); // Gris
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }

    private function estilarCabecera($rango) {
        $style = $this->sheet->getStyle($rango);
        $style->getFont()->setBold(true);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFE2E3E5'); // Gris claro
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function estilarTotales($rango) {
        $style = $this->sheet->getStyle($rango);
        $style->getFont()->setBold(true);
        $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFF3CD'); // Amarillo Alerta
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    private function descargarArchivo($nombre) {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombre . '.xlsx"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($this->spreadsheet);
        $writer->save('php://output');
        exit;
    }
}