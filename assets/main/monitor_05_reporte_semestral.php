<?php
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'monitor') {
    header("Location: ../../login.php");
    exit();
}

require_once('../../conexion.php');
$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

// ---------------------------
// 1. Capturar filtros
// ---------------------------
$ano = $_GET['ano'] ?? date('Y');
$semestre = $_GET['semestre'] ?? '';

// Calcular rango de fechas según el semestre
$fecha_inicio = $semestre == 'II' ? "$ano-07-01" : "$ano-01-01";
$fecha_final  = $semestre == 'II' ? "$ano-12-31" : "$ano-06-30";

// ---------------------------
// 2. Consulta de eventos semestrales
// ---------------------------
$eventos = [];
if (!empty($semestre)) {
    $sql = "SELECT e.id_evento, e.nombre_evento, e.fecha_inicio, e.fecha_final,
                   l.nombre AS nombre_linea, e.tipo_orientacion, e.sede, e.modalidad, e.nivel_academico,
                   COUNT(DISTINCT a.cedula) AS beneficiarios_unicos
            FROM eventos e
            JOIN lineas_accion l ON e.id_linea_accion = l.id
            JOIN asistencia asi ON asi.id_evento = e.id_evento
            JOIN asistentes a ON asi.id = a.id
            WHERE e.fecha_inicio BETWEEN :inicio AND :fin
            GROUP BY e.id_evento
            ORDER BY e.fecha_inicio DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':inicio' => $fecha_inicio,
        ':fin'    => $fecha_final
    ]);
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte Semestral</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include_once("../../componentes/header.php");
HeaderPersonalizado::mostrar(); ?>

  <div class="container mt-4">
    <h3 class="mb-4">Reporte Semestral de Eventos</h3>

    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-3">
        <label for="ano" class="form-label">Año</label>
        <select class="form-select" name="ano" id="ano" required>
          <option value="">Seleccione...</option>
          <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
            <option value="<?= $y ?>" <?= isset($_GET['ano']) && $_GET['ano'] == $y ? 'selected' : '' ?>><?= $y ?></option>
          <?php endfor; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label for="semestre" class="form-label">Semestre</label>
        <select class="form-select" name="semestre" id="semestre" required>
          <option value="">Seleccione...</option>
          <option value="I" <?= isset($_GET['semestre']) && $_GET['semestre'] == 'I' ? 'selected' : '' ?>>I (Enero -Junio)</option>
          <option value="II" <?= isset($_GET['semestre']) && $_GET['semestre'] == 'II' ? 'selected' : '' ?>>II (Julio - Diciembre)</option>
        </select>
      </div>

<div class="d-flex justify-content-center gap-3 mt-2">
  <button type="submit" class="btn btn-outline-primary" id="btnAplicarFiltro">
    🔍 Aplicar Filtro
  </button>
  <a href="monitor_05_reporte_semestral.php" class="btn btn-outline-secondary" id="btnLimpiarFiltros">
    ♻️ Limpiar Filtros
  </a>
  <a href="monitor_generar_reporte_semestral_pdf.php?ano=<?= urlencode($ano) ?>&semestre=<?= urlencode($semestre) ?>" target="_blank" class="btn btn-outline-danger" id="btnExportarPDF">
    📄 Exportar a PDF
  </a>
</div>




    </form>


    <?php
    if (isset($_GET['ano'], $_GET['semestre'])) {
      $ano = $_GET['ano'];
      $semestre = $_GET['semestre'];

      $sql = "SELECT e.*, l.nombre AS linea_accion, p.nombre AS programa_responsable
              , e.tipo_orientacion, e.modalidad, e.nivel_academico
              FROM eventos e
              JOIN lineas_accion l ON e.id_linea_accion = l.id
              JOIN programas p ON e.programa_responsable = p.id_programa
              WHERE e.ano = ? AND e.semestre = ?
              ORDER BY e.fecha_inicio ASC";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$ano, $semestre]);
      $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ($eventos): ?>
        <table class="table table-bordered table-striped">
          <thead class="table-dark">
            <tr>
              <th>#</th>
              <th>Nombre del Evento</th>
              <th>Línea de Acción</th>
              <th>Programa Responsable</th>
              <th>Tipo de Orientación</th>
              <th>Modalidad</th>
              <th>Nivel Académico</th>
              <th>Fechas</th>
              <th>Sede</th>
              <th>Total Beneficiarios Únicos</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($eventos as $index => $evento):
              $id_evento = $evento['id_evento'];
              $stmt2 = $pdo->prepare("SELECT COUNT(DISTINCT id_asistente) AS total FROM asistencia WHERE id_evento = ?");
              $stmt2->execute([$id_evento]);
              $beneficiarios = $stmt2->fetch(PDO::FETCH_ASSOC)['total'];
            ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($evento['nombre_evento']) ?></td>
                <td><?= htmlspecialchars($evento['linea_accion']) ?></td>
                <td><?= htmlspecialchars($evento['programa_responsable']) ?></td>
                <td><?= htmlspecialchars($evento['tipo_orientacion'] ?? 'Academico') ?></td>
                <td><?= $evento['modalidad'] ?></td>
                <td><?= $evento['nivel_academico'] ?></td>
                <td><?= date('d/m/Y', strtotime($evento['fecha_inicio'])) . ($evento['fecha_inicio'] != $evento['fecha_final'] ? ' al ' . date('d/m/Y', strtotime($evento['fecha_final'])) : '') ?></td>
                <td><?= $evento['sede'] ?></td>
                <td><?= $beneficiarios ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="alert alert-warning">No se encontraron eventos para ese semestre.</div>
      <?php endif;
    }
    ?>
  </div>
</body>
<?php include_once("../../componentes/footer.php"); Footer::mostrar(); ?>
</html>
