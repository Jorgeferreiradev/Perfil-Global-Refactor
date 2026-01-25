<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-calendar-check me-2 text-primary"></i>Gestión de Eventos</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearEvento">
        <i class="fas fa-plus me-2"></i>Nuevo Evento
    </button>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/dashboard/eventos" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="linea" class="form-select">
                    <option value="">Todas las Líneas</option>
                    <?php foreach($lineas as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= ($_GET['linea'] ?? '') == $l['id'] ? 'selected' : '' ?>>
                            <?= $l['nombre_linea'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="programa" class="form-select">
                    <option value="">Todos los Programas</option>
                    <?php foreach($programas as $p): ?>
                        <option value="<?= $p['id_programa'] ?>" <?= ($_GET['programa'] ?? '') == $p['id_programa'] ? 'selected' : '' ?>>
                            <?= $p['nombre_programa'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="fecha" class="form-control" value="<?= $_GET['fecha'] ?? '' ?>">
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <input type="text" name="busqueda" class="form-control" placeholder="Buscar evento..." value="<?= $_GET['busqueda'] ?? '' ?>">
                    <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="col-md-1">
                 <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-danger w-100" title="Limpiar"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger">
        <?php 
            if($_GET['error'] == 'fecha_pasada') echo "Error: No puedes crear eventos en el pasado.";
            else echo "Ha ocurrido un error en la base de datos.";
        ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Evento / Línea</th>
                        <th>Programa Resp.</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($eventos as $evt): 
                        // LÓGICA DE ESTADOS CORREGIDA CON DATETIME
                        $ahora = new DateTime("now"); // Usa la zona horaria de config.php
                        $inicio = new DateTime($evt['fecha_inicio'] . ' ' . $evt['hora_inicio']);
                        $fin    = new DateTime($evt['fecha_final'] . ' ' . $evt['hora_final']);

                        if ($ahora < $inicio) {
                            $badge = '<span class="badge bg-primary">Programado</span>';
                        } elseif ($ahora >= $inicio && $ahora <= $fin) {
                            $badge = '<span class="badge bg-success">En Curso</span>';
                        } else {
                            $badge = '<span class="badge bg-secondary">Cerrado</span>';
                        }
                    ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($evt['nombre_evento']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($evt['nombre_linea']) ?></small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= htmlspecialchars($evt['programa_nombre'] ?? 'General') ?>
                            </span>
                        </td>
                        <td>
                            <?= $inicio->format('d/m/Y') ?><br>
                            <small><?= $inicio->format('H:i') ?> - <?= $fin->format('H:i') ?></small>
                        </td>
                        <td><?= $badge ?></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>/dashboard/eventos/qr/<?= $evt['token_qr'] ?>" 
                            target="_blank" 
                            class="btn btn-sm btn-outline-dark" 
                            title="Ver QR">
                                <i class="fas fa-qrcode"></i>
                            </a>

                            <a href="<?= BASE_URL ?>/dashboard/eventos/asistentes/<?= $evt['id_evento'] ?>" 
                            class="btn btn-sm btn-info text-white" 
                            title="Asistentes">
                                <i class="fas fa-users"></i>
                            </a>

                            <!-- EDITAR -->
                            <button type="button" 
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?= $evt['id_evento'] ?>"
                                    title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- ELIMINAR -->
                            <a href="<?= BASE_URL ?>/dashboard/eventos/eliminar/<?= $evt['id_evento'] ?>" 
                            class="btn btn-sm btn-danger"
                            title="Eliminar"
                            onclick="return confirm('¿Estás seguro de eliminar este evento?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>

                    </tr>

                    <div class="modal fade" id="modalEditar<?= $evt['id_evento'] ?>" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <form action="<?= BASE_URL ?>/dashboard/eventos/editar/<?= $evt['id_evento'] ?>" method="POST">
                                    
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title fw-bold">Editar Evento</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body text-start">

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Nombre del Evento</label>
                                            <input type="text"
                                                name="nombre_evento"
                                                class="form-control"
                                                value="<?= htmlspecialchars($evt['nombre_evento']) ?>"
                                                required>
                                        </div>

                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Fecha Inicio</label>
                                                <input type="date"
                                                    name="fecha_inicio"
                                                    class="form-control"
                                                    value="<?= $evt['fecha_inicio'] ?>"
                                                    required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Hora Inicio</label>
                                                <input type="time"
                                                    name="hora_inicio"
                                                    class="form-control"
                                                    value="<?= $evt['hora_inicio'] ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-6">
                                                <label class="form-label">Fecha Final</label>
                                                <input type="date"
                                                    name="fecha_final"
                                                    class="form-control"
                                                    value="<?= $evt['fecha_final'] ?>"
                                                    required>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">Hora Final</label>
                                                <input type="time"
                                                    name="hora_final"
                                                    class="form-control"
                                                    value="<?= $evt['hora_final'] ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <!-- Campos ocultos que el controlador espera -->
                                        <input type="hidden" name="linea_accion" value="<?= $evt['id_linea_accion'] ?>">
                                        <input type="hidden" name="programa_responsable" value="<?= $evt['programa_responsable'] ?>">
                                        <input type="hidden" name="sede" value="<?= $evt['sede'] ?>">

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Cancelar
                                        </button>
                                        <button type="submit" class="btn btn-warning fw-bold">
                                            Actualizar
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>



                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrearEvento" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?= BASE_URL ?>/dashboard/eventos/crear" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Nuevo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">Nombre del Evento</label>
                            <input type="text" name="nombre_evento" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Línea de Acción</label>
                            <select name="linea_accion" class="form-select" required>
                                <option value="" selected disabled>Seleccione...</option>
                                <?php foreach($lineas as $l): ?>
                                    <option value="<?= $l['id'] ?>"><?= $l['nombre_linea'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Programa Responsable</label>
                            <select name="programa_responsable" class="form-select" required>
                                <option value="" selected disabled>Seleccione...</option>
                                <?php foreach($programas as $p): ?>
                                    <option value="<?= $p['id_programa'] ?>"><?= $p['nombre_programa'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sede</label>
                            <select name="sede" class="form-select">
                                <option value="Cucuta">Cúcuta</option>
                                <option value="Ocaña">Ocaña</option>
                            </select>
                        </div>

                        <div class="col-12"><hr></div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Inicio</label>
                            <div class="input-group">
                                <input type="date" name="fecha_inicio" class="form-control" required min="<?= date('Y-m-d') ?>">
                                <input type="time" name="hora_inicio" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fin</label>
                            <div class="input-group">
                                <input type="date" name="fecha_final" class="form-control" required min="<?= date('Y-m-d') ?>">
                                <input type="time" name="hora_final" class="form-control" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>