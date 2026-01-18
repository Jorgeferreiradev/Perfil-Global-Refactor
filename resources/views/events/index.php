<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-calendar-check me-2"></i>Gestión de Eventos</h2>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearEvento">
        <i class="fas fa-plus me-2"></i>Nuevo Evento
    </button>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Operación realizada con éxito.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Evento</th>
                        <th>Fecha/Hora</th>
                        <th>Sede</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($eventos as $evt): 
                        // Lógica visual simple para estado
                        $hoy = date('Y-m-d H:i:s');
                        $activo = ($hoy >= $evt['fecha_inicio'] . ' ' . $evt['hora_inicio'] 
                                && $hoy <= $evt['fecha_final'] . ' ' . $evt['hora_final']);
                    ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($evt['nombre_evento']) ?></strong><br>
                            <small class="text-muted">Línea: <?= $evt['id_linea_accion'] ?></small>
                        </td>
                        <td>
                            <?= $evt['fecha_inicio'] ?><br>
                            <small><?= $evt['hora_inicio'] ?> - <?= $evt['hora_final'] ?></small>
                        </td>
                        <td><?= htmlspecialchars($evt['sede']) ?></td>
                        <td>
                            <?php if($activo): ?>
                                <span class="badge bg-success">En Curso</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Cerrado</span>
                            <?php endif; ?>
                            
                            <?php if($evt['es_simulacion']): ?>
                                <span class="badge bg-warning text-dark" title="Dato de prueba">SIM</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/dashboard/eventos/qr/<?= $evt['token_qr'] ?>" target="_blank" class="btn btn-sm btn-outline-dark" title="Ver QR">
                                <i class="fas fa-qrcode"></i>
                            </a>
                            <a href="/dashboard/eventos/asistentes/<?= $evt['id_evento'] ?>" class="btn btn-sm btn-info text-white" title="Ver Lista">
                                <i class="fas fa-users"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrearEvento" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/dashboard/eventos/crear" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nombre del Evento</label>
                        <input type="text" name="nombre_evento" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Línea Acción (ID)</label>
                            <input type="number" name="linea_accion" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Sede</label>
                            <select name="sede" class="form-select">
                                <option value="Cucuta">Cúcuta</option>
                                <option value="Ocaña">Ocaña</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Hora Inicio</label>
                            <input type="time" name="hora_inicio" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Fecha Final</label>
                            <input type="date" name="fecha_final" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Hora Final</label>
                            <input type="time" name="hora_final" class="form-control" required>
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