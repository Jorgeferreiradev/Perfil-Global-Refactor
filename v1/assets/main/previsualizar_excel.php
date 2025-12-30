<?php
// Previsualización de un archivo Excel
// Este script permite al usuario cargar un archivo Excel y previsualizar su contenido antes de procesarlo.
// Se utiliza la biblioteca PhpSpreadsheet para manejar archivos Excel.

session_start();
include_once("../../componentes/header.php");
HeaderPersonalizado::mostrar();

if (!isset($_SESSION['rol']) || $_SESSION['rol']!=='administrador') {
    header("Location: ../../login.php");
    exit();
}

require_once("../../conexion.php");
$db   = new ConexionDB();
$conn = $db->obtenerConexion();



$errors = [];
$success = false;
require '../../vendor/autoload.php'; // Ajusta esta ruta según dónde esté tu "vendor/"

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['archivo'])) {
    $archivoTmp = $_FILES['archivo']['tmp_name'];

    if (!empty($archivoTmp)) {
        $spreadsheet = IOFactory::load($archivoTmp);
        $hoja = $spreadsheet->getActiveSheet();
        $filas = $hoja->toArray();

        echo "<h2>Previsualización del Excel</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";

        $filaNum = 1;
        foreach ($filas as $fila) {
            echo "<tr>";
            $columnasValidas = true;

            foreach ($fila as $columna) {
                $valor = trim($columna);
                $color = (empty($valor)) ? "red" : "black";

                echo "<td style='color: $color;'>$valor</td>";

                if (empty($valor)) {
                    $columnasValidas = false;
                }
            }

            echo "</tr>";

            if (!$columnasValidas) {
                echo "<tr><td colspan='3' style='color: red;'>⚠️ Fila $filaNum tiene datos faltantes.</td></tr>";
            }

            $filaNum++;
        }

        echo "</table>";
    } else {
        echo "No se pudo cargar el archivo.";
    }
} else {
?>
    <form method="POST" enctype="multipart/form-data">
        <label>Selecciona tu archivo Excel:</label><br>
        <input type="file" name="archivo" accept=".xls, .xlsx" required><br><br>
        <input type="submit" value="Previsualizar">
    </form>
<?php
}
?>
