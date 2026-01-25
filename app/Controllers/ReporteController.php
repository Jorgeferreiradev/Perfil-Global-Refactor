<?php
namespace App\Controllers;

use App\Models\Reporte;
use App\Models\Evento;
use App\Services\ExcelReportService; // ✅ Importante: Usamos el servicio profesional

class ReporteController {

    // VISTA PRINCIPAL (El panel de botones)
    public function index() {
        $eventoModel = new Evento();
        $listaEventos = $eventoModel->all(); 

        // Calcular semestre actual para mostrarlo en la vista
        $mes = date('n');
        $anio = date('Y');
        $semestreTxt = ($mes <= 6) ? "$anio-I (Ene - Jun)" : "$anio-II (Jul - Dic)";

        $title = "Centro de Reportes";
        $active = "reportes";

        require_once __DIR__ . '/../../resources/views/layouts/header.php';
        require_once __DIR__ . '/../../resources/views/layouts/sidebar.php';
        require_once __DIR__ . '/../../resources/views/dashboard/reportes.php';
        require_once __DIR__ . '/../../resources/views/layouts/footer.php';
    }

    // =========================================================
    // REPORTE 1: MATRIZ SEMESTRAL (Usa PhpSpreadsheet)
    // =========================================================
    public function descargarMatriz() {
        $reporteModel = new Reporte();
        $excelService = new ExcelReportService(); // Instanciamos el servicio de diseño

        // 1. Calcular Fechas Automáticas del Semestre
        $mes = date('n');
        $anio = date('Y');
        
        if ($mes <= 6) {
            $fi = "$anio-01-01"; 
            $ff = "$anio-06-30"; 
            $sem = "$anio-I";
        } else {
            $fi = "$anio-07-01"; 
            $ff = "$anio-12-31"; 
            $sem = "$anio-II";
        }

        // 2. Estructurar Datos (Empaquetamos todo para enviarlo al Servicio)
        $datosCompletos = [];
        // Obtenemos las líneas que tienen eventos en este rango de fechas
        $lineas = $reporteModel->getLineasConEventos($fi, $ff);

        foreach ($lineas as $nombreLinea) {
            // Para cada línea, guardamos sus eventos y sus totales reales
            $datosCompletos[$nombreLinea] = [
                'eventos' => $reporteModel->getEventosPorLinea($nombreLinea, $fi, $ff),
                'reales'  => $reporteModel->getBeneficiariosRealesPorLinea($nombreLinea, $fi, $ff)
            ];
        }

        // 3. Generar Excel Real (.xlsx)
        // Le pasamos el paquete de datos al "Arquitecto" para que lo dibuje
        $excelService->generarMatrizSemestral($sem, $datosCompletos);
        
        // No necesitamos exit aquí porque el servicio ya hace el output y exit
    }

    // =========================================================
    // REPORTE 2: INDIVIDUAL POR EVENTO (Usa PhpSpreadsheet)
    // =========================================================
    public function descargarIndividual() {
        $idEvento = $_POST['id_evento'];
        
        $reporteModel = new Reporte();
        $excelService = new ExcelReportService();

        // 1. Obtener información cruda de la base de datos
        $info = $reporteModel->getInfoEvento($idEvento);
        $asistentes = $reporteModel->getDetalleAsistentes($idEvento);

        // 2. Calcular resumen rápido (Contadores)
        $resumen = ['Estudiante' => 0, 'Docente' => 0, 'Administrativo' => 0, 'Egresado' => 0, 'Invitado' => 0];
        
        foreach ($asistentes as $a) {
            // Buscamos palabras clave en el tipo de vinculación
            if (strpos($a['tipo_vinculacion'], 'Estudiante') !== false) {
                $resumen['Estudiante']++;
            } elseif (strpos($a['tipo_vinculacion'], 'Docente') !== false) {
                $resumen['Docente']++;
            } elseif (strpos($a['tipo_vinculacion'], 'Administrativo') !== false) {
                $resumen['Administrativo']++;
            } elseif (strpos($a['tipo_vinculacion'], 'Egresado') !== false) {
                $resumen['Egresado']++;
            } else {
                $resumen['Invitado']++;
            }
        }

        // 3. Generar Excel Real (.xlsx)
        $excelService->generarIndividual($info, $resumen, $asistentes);
    }
}