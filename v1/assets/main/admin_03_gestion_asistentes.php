<?php
session_start();
include_once("../../componentes/header.php");
HeaderPersonalizado::mostrar("Admin – Gestión de Asistentes");

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../../login.php");
    exit();
}

require_once("../../conexion.php");
$db   = new ConexionDB();
$conn = $db->obtenerConexion();

// Obtener datos de asistentes
$sql = "SELECT a.*, p.nombre AS programa, t.tipo AS tipo_asistente
        FROM asistentes a
        JOIN programas p ON a.id_programa = p.id_programa
        JOIN tipos_asistentes t ON a.id_tipo = t.id_tipo
        ORDER BY a.nombre_completo ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$asistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Asistentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="p-4">
    <div class="container">
        <h3>Gestión de Asistentes</h3>

        <input type="text" id="busqueda" class="form-control mb-3" placeholder="Buscar por cédula...">

        <table class="table table-striped" id="tablaAsistentes">
            <thead class="table-dark">
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Programa</th>
                    <th>Tipo</th>
                    <th>Año</th>
                    <th>Semestre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($asistentes as $a): ?>
                <tr>
                    <td><?= htmlspecialchars($a['cedula']) ?></td>
                    <td><?= htmlspecialchars($a['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($a['programa']) ?></td>
                    <td><?= htmlspecialchars($a['tipo_asistente']) ?></td>
                    <td><?= htmlspecialchars($a['ano']) ?></td>
                    <td><?= htmlspecialchars($a['semestre']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-primary" onclick="editarAsistente(<?= $a['id'] ?>)">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarAsistente(<?= $a['id'] ?>)">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Filtro por cédula
        document.getElementById('busqueda').addEventListener('keyup', function () {
            const texto = this.value.toLowerCase();
            document.querySelectorAll('#tablaAsistentes tbody tr').forEach(fila => {
                fila.style.display = fila.cells[0].textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });

        // Cargar asistente en modal
        function editarAsistente(id) {
            $.ajax({
                url: 'get_asistente_por_cedula.php',
                method: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function (data) {
                    $('#id').val(data.id);
                    $('#cedula').val(data.cedula);
                    $('#nombre_completo').val(data.nombre_completo);
                    $('#id_programa').val(data.id_programa);
                    $('#id_tipo').val(data.id_tipo);
                    $('#ano').val(data.ano);
                    $('#semestre').val(data.semestre);
                    new bootstrap.Modal(document.getElementById('modalEditar')).show();
                },
                error: function () {
                    alert('Error al obtener los datos del asistente.');
                }
            });
        }

        // Eliminar asistente
        function eliminarAsistente(id) {
            if (confirm("¿Estás seguro de eliminar este asistente?")) {
                window.location.href = "eliminar_asistente.php?id=" + id;
            }
        }
    </script>
    <!-- Modal Editar Asistente -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEditar" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar Asistente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id" name="id">
        <div class="mb-2">
          <label for="cedula" class="form-label">Numero de Documento</label>
          <input type="text" class="form-control" id="cedula" name="cedula" required>
        </div>
        <div class="mb-2">
          <label for="nombre_completo" class="form-label">Nombre Completo</label>
          <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
        </div>
        <div class="mb-2">
          <label for="id_programa" class="form-label">Programa</label>
          <select class="form-select" id="id_programa" name="id_programa" required>
            
          <!-- Opciones llenadas por PHP -->
            <?php
            $stmtP = $conn->query("SELECT id_programa, nombre FROM programas");
            while ($row = $stmtP->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id_programa']}'>{$row['nombre']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="mb-2">
          <label for="id_tipo" class="form-label">Tipo Asistente</label>
          <select class="form-select" id="id_tipo" name="id_tipo" required>
            <!-- Opciones llenadas por PHP -->
            <?php
            $stmtT = $conn->query("SELECT id_tipo, tipo FROM tipos_asistentes");
            while ($row = $stmtT->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id_tipo']}'>{$row['tipo']}</option>";
            }
            ?>
          </select>
        </div>
        <div class="mb-2">
          <label for="ano" class="form-label">Año</label>
          <input type="number" class="form-control" id="ano" name="ano" required>
        </div>
        <div class="mb-2">
          <label for="semestre" class="form-label">Semestre</label>
          <select class="form-select" id="semestre" name="semestre" required>
            <option value="1">I (Enero-Junio)</option>
            <option value="2">II (Julio-Diciembre)</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script>
  // Enviar formulario por AJAX
  $('#formEditar').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
      url: 'editar_asistente.php',
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          alert('Asistente actualizado correctamente.');
          location.reload();
        } else {
          alert(response.message || 'Ocurrió un error.');
        }
      },
      error: function() {
        alert('Error de red al guardar.');
      }
    });
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
<?php include_once("../../componentes/footer.php");
Footer::mostrar(); ?>
</html>