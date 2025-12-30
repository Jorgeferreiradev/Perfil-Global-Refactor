<?php
session_start();

// Verifica si el usuario es administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../login.php");
    exit();
}

// Conexión y carga de dependencias
require_once("../../conexion.php");
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$db   = new ConexionDB();
$conn = $db->obtenerConexion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carga de Asistentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <?php 
    include_once("../../componentes/header.php");
    HeaderPersonalizado::mostrar();
    ?>
<center>
    <div class="container">
        <h3 class="mb-4">Cargar Asistentes desde Excel</h3>

        <!-- Formulario de carga -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Selecciona tu archivo Excel:</label>
                <input type="file" class="form-control" name="archivo" accept=".xls,.xlsx" required>
            </div>

            <div class="mb-3">
                <label class="form-label">¿A qué bloque del semestre pertenece?</label>
                <select class="form-select" name="semestre" required>
                    <option value="">Seleccionar...</option>
                    <option value="Enero-Junio">I (Enero-Junio)</option>
                    <option value="Julio-Diciembre">II (Julio-Diciembre)</option>
                </select>
            </div>

        <div class="mb-3">
                <label class="form-label">Año:</label>
                <select name="anio" class="form-select" required>
                <option value="<?= date('Y') ?>" selected><?= date('Y') ?></option>
                </select>
        </div>

            <!-- Botones de acción -->
            <button type="submit" name="accion" value="previsualizar" class="btn btn-warning">Previsualizar</button>
            <button type="submit" name="accion" value="guardar" class="btn btn-success">Guardar en Base de Datos</button>
        </form></center>

        <?php
        // Funciones para obtener IDs de programa y tipo de asistente
        function obtenerIdPrograma($nombre, $conn) {
            $stmt = $conn->prepare("SELECT id_programa FROM programas WHERE nombre = ?");
            $stmt->execute([$nombre]);
            return $stmt->fetchColumn();
        }

        function obtenerIdTipoAsistente($tipo, $conn) {
            $stmt = $conn->prepare("SELECT id_tipo FROM tipos_asistentes WHERE tipo = ?");
            $stmt->execute([$tipo]);
            return $stmt->fetchColumn();
        }

        // Procesamiento del archivo
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
            $archivoTmp = $_FILES['archivo']['tmp_name'];
            $accion = $_POST['accion'];
            $anio = $_POST['anio'];
            $semestre = $_POST['semestre'];

            $spreadsheet = IOFactory::load($archivoTmp);
            $hoja = $spreadsheet->getActiveSheet();
            $filas = $hoja->toArray();
            $cabecera = array_shift($filas); // Elimina la cabecera

            // Agrega estas variables para evitar warnings si se usan más abajo
$insertados = 0;
$repetidos = 0;
$fallidos   = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $archivoTmp = $_FILES['archivo']['tmp_name'];
    $accion     = $_POST['accion'];
    $anio       = $_POST['anio'];
    $semestre   = $_POST['semestre'];

    $spreadsheet = IOFactory::load($archivoTmp);
    $hoja        = $spreadsheet->getActiveSheet();
    $filas       = $hoja->toArray();
    $cabecera    = array_shift($filas); // Elimina la cabecera

    if ($accion === 'previsualizar') {
        echo "<h4 class='mt-4'>Previsualización con validación:</h4>";
        echo "<table class='table table-bordered table-sm'>";
        echo "<thead><tr>";

        foreach ($cabecera as $col) {
            echo "<th>" . htmlspecialchars($col) . "</th>";
        }
        echo "<th>Validación Documento</th>";
        echo "</tr></thead><tbody>";

        foreach ($filas as $fila) {
            [$cedula, $nombre, $programa_nombre, $anio_excel, $semestre_excel, $tipo_asistente] = array_map('trim', $fila);
            $errores = [];

            if (empty($cedula) || empty($nombre) || empty($programa_nombre) || empty($anio_excel) || empty($semestre_excel) || empty($tipo_asistente)) {
                $errores[] = "Campos vacíos.";
            }

            // Validar existencia de programa y tipo de asistente
            $id_programa = obtenerIdPrograma($programa_nombre, $conn);
            $id_tipo     = obtenerIdTipoAsistente($tipo_asistente, $conn);

            if (!$id_programa) {
                $errores[] = "Programa inválido.";
            }

            if (!$id_tipo) {
                $errores[] = "Tipo de asistente inválido.";
            }

            // Verificar duplicado
            $stmt = $conn->prepare("SELECT id FROM asistentes WHERE cedula = ?");
            $stmt->execute([$cedula]);
            if ($stmt->rowCount() > 0) {
                $errores[] = "Cédula ya registrada.";
            }

            // Mostrar fila
            echo "<tr>";
            foreach ($fila as $valor) {
                echo "<td>" . htmlspecialchars($valor) . "</td>";
            }

            // Mostrar errores (o "✓ Todo bien")
            if (!empty($errores)) {
                echo "<td><span class='text-danger'>" . implode("<br>", $errores) . "</span></td>";
            } else {
                echo "<td><span class='text-success'>✓ Todo bien</span></td>";
            }

            echo "</tr>";
        }

        echo "</tbody></table>";
    }

    if ($accion === 'guardar') {
        foreach ($filas as $fila) {
            [$cedula, $nombre, $programa_nombre, $anio_excel, $semestre_excel, $tipo_asistente] = array_map('trim', $fila);

            if (empty($cedula) || empty($nombre) || empty($programa_nombre) || empty($anio_excel) || empty($semestre_excel) || empty($tipo_asistente)) {
                $fallidos++;
                continue;
            }

            // Verificar duplicados
            $stmt = $conn->prepare("SELECT id FROM asistentes WHERE cedula = ?");
            $stmt->execute([$cedula]);
            if ($stmt->rowCount() > 0) {
                $repetidos++;
                continue;
            }

            $id_programa = obtenerIdPrograma($programa_nombre, $conn);
            $id_tipo     = obtenerIdTipoAsistente($tipo_asistente, $conn);

            if (!$id_programa || !$id_tipo) {
                $fallidos++;
                continue;
            }

            // Insertar en la base de datos
            $sql  = "INSERT INTO asistentes (cedula, nombre_completo, id_programa, id_tipo, ano, semestre)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$cedula, $nombre, $id_programa, $id_tipo, $anio, $semestre]);
            $insertados++;
        }


        }
}

            
            if ($accion === 'guardar') {
                // Guardar en la base de datos
                $insertados = 0;
                $repetidos = 0;
                $fallidos = 0;

                foreach ($filas as $fila) {
                    [$cedula, $nombre, $programa_nombre, $anio_excel, $semestre_excel, $tipo_asistente] = array_map('trim', $fila);

                    // Validaciones básicas
                    if (empty($cedula) || empty($nombre) || empty($programa_nombre) || empty($anio_excel) || empty($semestre_excel) || empty($tipo_asistente)) {
                        $fallidos++;
                        continue;
                    }

                    // Verificar duplicados
                    $stmt = $conn->prepare("SELECT id FROM asistentes WHERE cedula = ?");
                    $stmt->execute([$cedula]);
                    if ($stmt->rowCount() > 0) {
                        $repetidos++;
                        continue;
                    }

                    $id_programa = obtenerIdPrograma($programa_nombre, $conn);
                    $id_tipo = obtenerIdTipoAsistente($tipo_asistente, $conn);

                    if (!$id_programa || !$id_tipo) {
                        $fallidos++;
                        continue;
                    }

                    // Insertar en la base de datos
                    $sql = "INSERT INTO asistentes (cedula, nombre_completo, id_programa, id_tipo_asistente, ano, semestre)
                            VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$cedula, $nombre, $id_programa, $id_tipo, $anio, $semestre]);
                    $insertados++;
                }

                // Resumen de la carga
                echo "<div class='alert alert-info mt-4'>
                        <strong>Resumen de carga:</strong><br>
                        ✅ Insertados: $insertados<br>
                        ⚠️ Repetidos (omitidos): $repetidos<br>
                        ❌ Fallidos: $fallidos
                    </div>";
            }
        }
        ?>
    </div>
</body>
<script>
    setTimeout(function() {
        let alerta = document.querySelector(".alert-info");
        if (alerta) {
            alerta.style.transition = "opacity 0.5s";
            alerta.style.opacity = "0";
            setTimeout(() => alerta.remove(), 500);
        }
    }, 5000); // Desaparece en 5 segundos
</script>

<?php 
include_once("../../componentes/footer.php");
Footer::mostrar();
?>

</html>