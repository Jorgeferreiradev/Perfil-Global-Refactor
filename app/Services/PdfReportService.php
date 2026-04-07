<?php
namespace App\Services;

use TCPDF;

class PdfReportService {

    private $pdf;

    public function __construct() {
        // Inicializar TCPDF (Orientación Landscape para Matriz, Portrait para Individual)
        $this->pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Configuración básica
        $this->pdf->SetCreator('PerfilGlobal v2');
        $this->pdf->SetAuthor('Bienestar Institucional');
        $this->pdf->SetMargins(15, 15, 15);
        $this->pdf->SetAutoPageBreak(TRUE, 15);
        $this->pdf->setPrintHeader(false); // Simplificamos quitando header por defecto
        $this->pdf->setPrintFooter(false);
    }

    // ==========================================
    // 📊 REPORTE 1: MATRIZ SEMESTRAL (PDF)
    // ==========================================
    public function generarMatrizSemestral($semestre, $datosPorLinea) {
        $this->pdf->AddPage('L'); // Horizontal
        $this->pdf->SetFont('helvetica', 'B', 16);
        
        // Título
        $this->pdf->SetFillColor(13, 110, 253); // Azul
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->Cell(0, 10, "MATRIZ GENERAL - SEMESTRE $semestre", 0, 1, 'C', 1);
        $this->pdf->Ln(5);

        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 10);

        foreach ($datosPorLinea as $nombreLinea => $data) {
            // Subtítulo Línea
            $this->pdf->SetFont('helvetica', 'B', 11);
            $this->pdf->SetFillColor(220, 220, 220); // Gris claro
            $this->pdf->Cell(0, 8, "LÍNEA DE ACCIÓN: " . strtoupper($nombreLinea), 0, 1, 'L', 1);
            
            // Encabezados Tabla
            $this->pdf->SetFont('helvetica', 'B', 9);
            $w = [80, 25, 25, 20, 20, 20, 20, 20, 20]; // Anchos
            $headers = ['Actividad', 'Inicio', 'Fin', 'Est', 'Doc', 'Adm', 'Grad', 'Ext', 'TOT'];
            
            foreach($headers as $i => $h) {
                $this->pdf->Cell($w[$i], 7, $h, 1, 0, 'C');
            }
            $this->pdf->Ln();

            // Filas
            $this->pdf->SetFont('helvetica', '', 9);
            foreach ($data['eventos'] as $ev) {
                // MultiCell para que el nombre baje de renglón si es largo
                $nb = $this->pdf->getNumLines($ev['nombre_evento'], $w[0]);
                $h = 6 * $nb; // Altura dinámica
                
                // Comprobamos salto de página
                if($this->pdf->GetY() + $h > $this->pdf->getPageHeight() - 15) {
                    $this->pdf->AddPage('L');
                }

                $this->pdf->MultiCell($w[0], $h, $ev['nombre_evento'], 1, 'L', 0, 0);
                $this->pdf->Cell($w[1], $h, $ev['fecha_inicio'], 1, 0, 'C');
                $this->pdf->Cell($w[2], $h, $ev['fecha_final'], 1, 0, 'C');
                $this->pdf->Cell($w[3], $h, $ev['est'], 1, 0, 'C');
                $this->pdf->Cell($w[4], $h, $ev['doc'], 1, 0, 'C');
                $this->pdf->Cell($w[5], $h, $ev['adm'], 1, 0, 'C');
                $this->pdf->Cell($w[6], $h, $ev['grad'], 1, 0, 'C');
                $this->pdf->Cell($w[7], $h, $ev['ext'], 1, 0, 'C');
                $this->pdf->Cell($w[8], $h, $ev['total_bruto'], 1, 1, 'C'); // Salto de línea
            }

            // Totales Reales
            $this->pdf->Ln(2);
            $this->pdf->SetFont('helvetica', 'B', 9);
            $this->pdf->SetFillColor(255, 243, 205); // Amarillo
            $this->pdf->Cell($w[0]+$w[1]+$w[2], 8, "BENEFICIARIOS REALES (Sin Repetir):", 1, 0, 'R', 1);
            $reales = $data['reales'];
            $this->pdf->Cell($w[3], 8, $reales['real_est'], 1, 0, 'C', 1);
            $this->pdf->Cell($w[4], 8, $reales['real_doc'], 1, 0, 'C', 1);
            $this->pdf->Cell($w[5], 8, $reales['real_adm'], 1, 0, 'C', 1);
            $this->pdf->Cell($w[6], 8, $reales['real_grad'], 1, 0, 'C', 1);
            $this->pdf->Cell($w[7], 8, $reales['real_ext'], 1, 0, 'C', 1);
            $this->pdf->Cell($w[8], 8, array_sum($reales), 1, 1, 'C', 1);
            
            $this->pdf->Ln(5);
        }

        $this->pdf->Output("Matriz_$semestre.pdf", 'D');
        exit;
    }

    // ==========================================
    // 📄 REPORTE 2: INDIVIDUAL (PDF)
    // ==========================================
    public function generarIndividual($info, $resumen, $asistentes) {
        $this->pdf->AddPage('P'); // Vertical
        
        // Título
        $this->pdf->SetFont('helvetica', 'B', 14);
        $this->pdf->SetFillColor(13, 110, 253);
        $this->pdf->SetTextColor(255, 255, 255);
        $this->pdf->Cell(0, 10, "REPORTE INDIVIDUAL DE ACTIVIDAD", 0, 1, 'C', 1);
        $this->pdf->Ln(5);

        // Ficha Técnica
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->SetFont('helvetica', '', 10);
        
        $html = "
        <table border=\"1\" cellpadding=\"5\">
            <tr style=\"background-color:#f2f2f2;\"><td><b>Línea:</b> {$info['linea_accion']}</td></tr>
            <tr><td><b>Actividad:</b> {$info['nombre_evento']}</td></tr>
            <tr style=\"background-color:#f2f2f2;\"><td><b>Fechas:</b> {$info['fecha_inicio']} al {$info['fecha_final']}</td></tr>
            <tr><td><b>Total Asistentes:</b> " . count($asistentes) . "</td></tr>
        </table>";
        
        $this->pdf->writeHTML($html, true, false, true, false, '');
        $this->pdf->Ln(5);

        // Resumen
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(0, 8, "RESUMEN DE POBLACIÓN", 0, 1, 'L');
        $this->pdf->SetFont('helvetica', '', 10);
        
        $htmlResumen = "
        <table border=\"1\" cellpadding=\"5\" style=\"text-align:center;\">
            <tr style=\"background-color:#0d6efd; color:white; font-weight:bold;\">
                <th>Estudiantes</th><th>Docentes</th><th>Admin</th><th>Graduados</th><th>Externos</th>
            </tr>
            <tr>
                <td>{$resumen['Estudiante']}</td>
                <td>{$resumen['Docente']}</td>
                <td>{$resumen['Administrativo']}</td>
                <td>{$resumen['Graduado']}</td>
                <td>{$resumen['Invitado']}</td>
            </tr>
        </table>";
        
        $this->pdf->writeHTML($htmlResumen, true, false, true, false, '');
        $this->pdf->Ln(5);

        // Listado
        $this->pdf->SetFont('helvetica', 'B', 11);
        $this->pdf->Cell(0, 8, "LISTADO DE ASISTENCIA", 0, 1, 'L');
        
        $htmlLista = '<table border="1" cellpadding="3">
            <tr style="background-color:#e9ecef; font-weight:bold;">
                <td width="20%">Documento</td>
                <td width="35%">Nombre</td>
                <td width="25%">Programa</td>
                <td width="20%">Fecha</td>
            </tr>';
            
        foreach ($asistentes as $p) {
            $htmlLista .= '<tr>
                <td>'.$p['numero_documento'].'</td>
                <td>'.$p['nombre_completo'].'</td>
                <td>'.$p['programa_dependencia'].'</td>
                <td>'.$p['fecha_asistencia'].'</td>
            </tr>';
        }
        $htmlLista .= '</table>';

        $this->pdf->SetFont('helvetica', '', 9);
        $this->pdf->writeHTML($htmlLista, true, false, true, false, '');

        // Preparar el nombre dinámico del archivo
        $nombreLimpio = str_replace(' ', '_', strtoupper($info['nombre_evento']));
        $nombreArchivo = 'Reporte_' . $nombreLimpio . '.pdf';
        
        // Forzar la descarga con el nuevo nombre ('D' es de Download)
        $this->pdf->Output($nombreArchivo, 'D');
        exit;
    }
}