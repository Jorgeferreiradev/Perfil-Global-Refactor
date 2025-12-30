<?php
session_start();

// Seguridad: Solo usuarios con rol "monitor" pueden entrar
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'monitor') {
    header("Location: ../../login.php");
    exit();
}

require_once('../../conexion.php');
require_once('../../componentes/header.php');

$conexion = new ConexionDB();
$pdo = $conexion->obtenerConexion();

// Consulta de eventos para el selector
$eventos = $pdo->query("SELECT id_evento, nombre_evento, fecha_inicio FROM eventos ORDER BY fecha_inicio DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Asistentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">

<?php HeaderPersonalizado::mostrar(); ?>

<div class="container mt-5" style="max-width: 600px;">
    <h2 class="mb-4 text-center">📝 Registro de Asistencias</h2>

    <?php if (empty($eventos)): ?>
        <div class="alert alert-warning text-center">⚠️ No hay eventos registrados aún.</div>
    <?php else: ?>
        <form id="registroAsistente" method="POST">
            <!-- Selector de evento -->
            <div class="mb-3">
                <label class="form-label">🎯 Selecciona un evento:</label>
                <select name="id_evento" id="id_evento" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <?php foreach ($eventos as $evento): ?>
                        <option value="<?= $evento['id_evento'] ?>">
                            <?= htmlspecialchars($evento['nombre_evento']) ?> (<?= $evento['fecha_inicio'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Input de cédula -->
            <div class="mb-3">
                <label class="form-label">🆔 Numero Documento del Asistente</label>
                <input type="text" name="cedula" id="cedula" placeholder="Ingresa cédula y presiona Enter" class="form-control" required autofocus>
                <div id="mensaje_no_encontrado" class="text-danger mt-1" style="display:none;">❌ No encontrado. Debe registrarlo.</div>
                <div class="d-flex justify-content-between mt-2">
                    <button type="button" class="btn btn-outline-primary" id="btnVerDatos">👁️ Ver Datos</button>
                    <button type="button" class="btn btn-outline-danger" id="btnEliminar">🗑️ Eliminar Asistencia</button>
                </div>
            </div>

            <!-- Botones -->
            <div class="text-center mb-3">
                <button type="submit" class="btn btn-success">✅ Registrar Asistencia</button>
                <button type="button" class="btn btn-warning" id="btnInvitado">👤 Registrar Invitado</button>
            </div>
        </form>

        <!-- Resumen dinámico -->
        <div id="mensaje_respuesta" class="mt-3 text-center"></div>
        <div id="resumen_asistencia" class="alert alert-info d-none"></div>
    <?php endif; ?>
</div>

<!-- Modal Datos Asistente -->
<div class="modal fade" id="modalDatos" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">👤 Información del Asistente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="contenidoModalDatos"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal para registrar invitado -->
<!-- Modal para registrar invitado -->
<div class="modal fade" id="modalInvitado" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Registrar Invitado</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Ingresa la cédula del invitado:</p>
        <input type="text" name="cedula" id="formInvitadoCedula" class="form-control" required>
        <input type="hidden" name="id_evento" id="formInvitadoEvento">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btnIrFormularioRegistro">Continuar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- ... -->

<script>
document.getElementById('btnIrFormularioRegistro').addEventListener('click', function () {
    const cedula = document.getElementById('formInvitadoCedula').value;
    const id_evento = document.getElementById('formInvitadoEvento').value;

    if (!cedula || !id_evento) {
        alert("⚠️ Faltan datos para redirigir.");
        return;
    }

    // Redirigir con los datos en la URL
    window.location.href = `monitor_registrar_asistente_form.php?cedula=${cedula}&id_evento=${id_evento}`;
});


$(document).ready(function () {
    const modalDatos = new bootstrap.Modal(document.getElementById('modalDatos'));
    const modalInvitado = new bootstrap.Modal(document.getElementById('modalInvitado'));
    let bloqueado = false;

    const eventoGuardado = localStorage.getItem('eventoSeleccionado');
    if (eventoGuardado) $('#id_evento').val(eventoGuardado);

    $('#id_evento').on('change', function () {
        const id_evento = $(this).val();
        localStorage.setItem('eventoSeleccionado', id_evento);
        if (id_evento) actualizarResumen(id_evento);
    });

    $('#cedula').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#registroAsistente').submit();
        }
    });

    $('#cedula').on('input', function () {
        const cedula = $(this).val().trim();
        if (cedula.length >= 5) {
            $.get('monitor_ver_asistente.php', { cedula }, function (datos) {
                const noEncontrado = datos.includes('No se encontró');
                $('#mensaje_no_encontrado').toggle(noEncontrado);
            });
        } else {
            $('#mensaje_no_encontrado').hide();
        }
    });

    $('#registroAsistente').on('submit', function (e) {
        e.preventDefault();
        if (bloqueado) return;
        bloqueado = true;
        setTimeout(() => bloqueado = false, 2000);

        const cedula = $('#cedula').val().trim();
        const id_evento = $('#id_evento').val();

        if (!cedula || !id_evento) return mostrarMensaje('danger', '❗ Selecciona un evento y escribe una cédula válida.');
        if (!/^\d{5,}$/.test(cedula)) return mostrarMensaje('warning', '⚠️ La cédula debe tener al menos 5 números.');

        $.post('monitor_registrar_asistente.php', { cedula, id_evento }, function (resp) {
            mostrarMensaje('success', `✅ ${resp}`);
            $('#cedula').val('').focus();
            $('#mensaje_no_encontrado').hide();
            actualizarResumen(id_evento);
        });
    });

    $('#btnVerDatos').on('click', function () {
        const cedula = $('#cedula').val().trim();
        if (!cedula) return mostrarMensaje('warning', '⚠️ Ingresa una cédula primero.');

        $.get('monitor_ver_asistente.php', { cedula }, function (datos) {
            $('#contenidoModalDatos').html(datos);
            modalDatos.show();
        });
    });

    $('#btnEliminar').on('click', function () {
        const cedula = $('#cedula').val().trim();
        const id_evento = $('#id_evento').val();
        if (!cedula || !id_evento) return mostrarMensaje('warning', '⚠️ Completa cédula y evento.');

        if (confirm('❌ ¿Eliminar asistencia de este evento?')) {
            $.post('monitor_eliminar_asistencia.php', { cedula, id_evento }, function (resp) {
                mostrarMensaje('danger', resp);
                actualizarResumen(id_evento);
            });
        }
    });

    $('#btnInvitado').click(function (e) {
        e.preventDefault();

        const cedula = $('#cedula').val();
        const evento = $('#id_evento').val();

        if (!evento) {
            alert("⚠️ Debes seleccionar un evento.");
            return;
        }

        if (!cedula) {
            alert("⚠️ Debes escribir una cédula primero.");
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'monitor_validar_cedula.php',
            data: { cedula },
            dataType: 'json',
            success: function (respuesta) {
                if (respuesta.success) {
                    // Cédula ya existe → bloquear
                    alert("⚠️ Esta cédula ya está registrada como asistente.");
                } else {
                    // Cédula NO existe → permitir registrar invitado
                    $('#modalInvitado').modal('show');
                    $('#formInvitadoCedula').val(cedula);
                    $('#formInvitadoEvento').val(evento);
                }
            },
            error: function () {
                alert("❌ Error al validar la cédula.");
            }
        });
    });

    function mostrarMensaje(tipo, mensaje) {
        const $mensaje = $('#mensaje_respuesta');
        $mensaje.html(`<div class='alert alert-${tipo}'>${mensaje}</div>`).fadeIn();

        setTimeout(() => {
            $mensaje.fadeOut(() => {
                $mensaje.empty().show(); // Solo vaciamos mensaje, no tocamos resumen
            });
        }, 3000);
    }

    function actualizarResumen(id_evento) {
        $.get('monitor_resumen_asistencias.php', { id_evento }, function (html) {
            console.log("Resumen recibido:", html);
            if (html.trim() !== '') {
                $('#resumen_asistencia').html(html).removeClass('d-none');
            }
        });
    }
});
</script>




</body>
</html>

<?php
include_once("../../componentes/footer.php");
Footer::mostrar();
?>
