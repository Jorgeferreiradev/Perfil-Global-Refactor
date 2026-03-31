<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-dark"><i class="fas fa-calendar-check me-2 text-primary"></i>Gestión de Eventos</h2>
    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearEvento">
        <i class="fas fa-plus me-2"></i>Nuevo Evento
    </button>
</div>

<div id="alert-container">
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>¡Excelente!</strong> 
            <?php 
                if($_GET['success'] == 'activado') echo "El evento ha sido reactivado exitosamente.";
                elseif($_GET['success'] == 'desactivado') echo "El evento ha sido desactivado (no aparecerá en reportes).";
                else echo "La operación se realizó correctamente.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>Error:</strong> Verifica los datos o intenta nuevamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
</div>

<div class="card mb-4 shadow-sm border-0 bg-light">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/dashboard/eventos" method="GET" class="row g-3">
            <div class="col-md-3">
                <select name="linea" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                    <option value="">Todas las Líneas</option>
                    <?php foreach($lineas as $l): ?>
                        <option value="<?= $l['id'] ?>" <?= (isset($_GET['linea']) && $_GET['linea'] == $l['id']) ? 'selected' : '' ?>>
                            <?= $l['nombre_linea'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="programa" class="form-select border-0 shadow-sm" onchange="this.form.submit()">
                    <option value="">Todos los Programas</option>
                    <?php foreach($programas as $p): ?>
                        <option value="<?= $p['id_programa'] ?>" <?= (isset($_GET['programa']) && $_GET['programa'] == $p['id_programa']) ? 'selected' : '' ?>>
                            <?= $p['nombre_programa'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                    <input type="date" name="fecha" class="form-control border-0 shadow-sm" 
                        value="<?= $_GET['fecha'] ?? '' ?>" 
                        onchange="this.form.submit()">
            </div>

            <div class="col-md-3">
                <div class="input-group shadow-sm">
                    <input type="text" name="busqueda" class="form-control border-0" placeholder="Buscar y presiona Enter..." value="<?= $_GET['busqueda'] ?? '' ?>">
                    <button type="submit" class="btn btn-white bg-white border-0"><i class="fas fa-search text-muted"></i></button>
                </div>
            </div>
            <div class="col-md-1">
                 <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-danger w-100 shadow-sm" title="Limpiar Filtros"><i class="fas fa-times"></i></a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">Evento / Línea</th>
                        <th>Programa Resp.</th>
                        <th>Horario</th>
                        <th>Sede</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($eventos)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No se encontraron eventos.</td></tr>
                    <?php else: ?>
                        <?php foreach($eventos as $evt): 
                            $isInactive = ($evt['estado'] == 'inactivo');
                            $rowClass = $isInactive ? 'bg-light text-muted opacity-75' : '';
                            
                            $ahora = new DateTime("now");
                            $inicio = new DateTime($evt['fecha_inicio'] . ' ' . $evt['hora_inicio']);
                            $fin    = new DateTime($evt['fecha_final'] . ' ' . $evt['hora_final']);

                            if ($isInactive) {
                                $badge = '<span class="badge bg-secondary px-3">Desactivado</span>';
                            } elseif ($ahora < $inicio) {
                                $badge = '<span class="badge rounded-pill bg-primary bg-opacity-10 text-primary px-3">Programado</span>';
                            } elseif ($ahora >= $inicio && $ahora <= $fin) {
                                $badge = '<span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3"><i class="fas fa-circle fa-xs me-1"></i>En Curso</span>';
                            } else {
                                $badge = '<span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3">Cerrado</span>';
                            }
                        ?>
                        <tr class="<?= $rowClass ?>">
                            <td class="ps-4">
                                <div class="fw-bold <?= $isInactive ? 'text-muted' : 'text-dark' ?> text-uppercase"><?= htmlspecialchars($evt['nombre_evento']) ?></div>
                                <small class="<?= $isInactive ? 'text-muted' : 'text-info' ?>"><i class="fas fa-tag me-1"></i><?= htmlspecialchars($evt['nombre_linea']) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal">
                                    <?= htmlspecialchars($evt['programa_nombre'] ?? 'General') ?>
                                </span>
                            </td>
                            <td>
                                <div class="small fw-bold"><?= $inicio->format('d/m/Y') ?></div>
                                <small><?= $inicio->format('H:i') ?> - <?= $fin->format('H:i') ?></small>
                            </td>
                            <td><?= $evt['sede'] ?></td>
                            <td><?= $badge ?></td>
                            
                            <td class="text-end pe-4">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm border shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-cog text-secondary"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                        <li><h6 class="dropdown-header text-uppercase small fw-bold">Opciones</h6></li>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/eventos/qr/<?= $evt['token_qr'] ?>" 
                                        target="_blank"><i class="fas fa-qrcode me-2"></i> Ver QR</a></li>
                                        <li><a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/eventos/asistentes/<?= $evt['id_evento'] ?>">
                                            <i class="fas fa-users me-2 text-info"></i> Asistentes</a></li>
                                        <li>
                                            <button class="dropdown-item" onclick="copiarLink('<?= BASE_URL ?>/asistencia/<?= $evt['token_qr'] ?>')">
                                                <i class="fas fa-link me-2 text-primary"></i> Copiar Link Registro
                                            </button>
                                        </li>
                                        

                                        <li><hr class="dropdown-divider"></li>
                                        
                                        <li>
                                            <button class="dropdown-item btn-editar" 
                                                data-id="<?= $evt['id_evento'] ?>"
                                                data-nombre="<?= $evt['nombre_evento'] ?>"
                                                data-linea="<?= $evt['id_linea_accion'] ?>"
                                                data-programa="<?= $evt['programa_responsable'] ?>"
                                                data-sede="<?= $evt['sede'] ?>"
                                                data-fini="<?= $evt['fecha_inicio'] ?>"
                                                data-hini="<?= $evt['hora_inicio'] ?>"
                                                data-ffin="<?= $evt['fecha_final'] ?>"
                                                data-hfin="<?= $evt['hora_final'] ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditarEvento">
                                                <i class="fas fa-edit me-2 text-warning"></i> Editar
                                            </button>
                                        </li>

                                        <?php if($isInactive): ?>
                                            <li><a class="dropdown-item text-success fw-bold" href="<?= BASE_URL ?>/dashboard/eventos/estado/<?= $evt['id_evento'] ?>/activo"><i class="fas fa-check-circle me-2"></i> Reactivar</a></li>
                                        <?php else: ?>
                                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>/dashboard/eventos/estado/<?= $evt['id_evento'] ?>/inactivo" onclick="return confirm('¿Al desactivarlo NO aparecerá en reportes. Continuar?')"><i class="fas fa-ban me-2"></i> Desactivar</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCrearEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form action="<?= BASE_URL ?>/dashboard/eventos/crear" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i>Nuevo Evento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Nombre de la Actividad</label>
                                <input type="text" name="nombre_evento" class="form-control form-control-lg fw-bold" oninput="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Línea de Acción</label>
                                <select name="linea_accion" class="form-select" required>
                                    <option value="" selected disabled>Seleccione...</option>
                                    <?php foreach($lineas as $l): ?>
                                        <option value="<?= $l['id'] ?>"><?= $l['nombre_linea'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                          <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Programa Responsable</label>
                                <select class="form-select select-search" name="programa_responsable" required>
                                    <option value="">Seleccione o busque un programa...</option>
                                    <?php foreach ($programas as $prog): ?>
                                        <?php 
                                            // LÓGICA SENIOR: Selecciona automáticamente el programa marcado como default en la Base de Datos
                                            $isDefault = ($prog['es_default'] == 1) ? 'selected' : ''; 
                                        ?>
                                        <option value="<?= $prog['id_programa'] ?>" <?= $isDefault ?>>
                                            <?= htmlspecialchars($prog['nombre_programa']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Sede</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check"><input class="form-check-input" type="radio" name="sede" value="Cúcuta" checked><label class="form-check-label">Cúcuta</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="sede" value="Ocaña"><label class="form-check-label">Ocaña</label></div>
                                    <div class="form-check"><input class="form-check-input" type="radio" name="sede" value="Virtual"><label class="form-check-label">Virtual</label></div>

                                </div>
                            </div>
                            <div class="col-12"><hr class="text-muted opacity-25"></div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Inicio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-calendar text-primary"></i></span>
                                    <input type="date" name="fecha_inicio" class="form-control" required min="<?= date('Y-m-d') ?>">
                                    <input type="time" name="hora_inicio" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Fin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-flag-checkered text-danger"></i></span>
                                    <input type="date" name="fecha_final" class="form-control" required min="<?= date('Y-m-d') ?>">
                                    <input type="time" name="hora_final" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Guardar Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarEvento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <form id="formEditarEvento" action="" method="POST">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i>Editar Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Nombre de la Actividad</label>
                                <input type="text" name="nombre_evento" id="edit_nombre" class="form-control form-control-lg fw-bold" oninput="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Línea de Acción</label>
                                <select name="linea_accion" id="edit_linea" class="form-select" required>
                                    <?php foreach($lineas as $l): ?>
                                        <option value="<?= $l['id'] ?>"><?= $l['nombre_linea'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Programa Responsable</label>
                                <select class="form-select" name="programa_responsable" id="edit_programa" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach ($programas as $prog): ?>
                                        <option value="<?= $prog['id_programa'] ?>"><?= $prog['nombre_programa'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-bold small text-uppercase text-muted">Sede</label>
                                <select name="sede" id="edit_sede" class="form-select w-auto">
                                    <option value="Cúcuta">Cúcuta</option>
                                    <option value="Ocaña">Ocaña</option>
                                    <option value="Virtual">Virtual</option>
                                </select>
                            </div>
                            <div class="col-12"><hr class="text-muted opacity-25"></div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Inicio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-calendar text-primary"></i></span>
                                    <input type="date" name="fecha_inicio" id="edit_fini" class="form-control" required>
                                    <input type="time" name="hora_inicio" id="edit_hini" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold small text-uppercase text-muted">Fin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-flag-checkered text-danger"></i></span>
                                    <input type="date" name="fecha_final" id="edit_ffin" class="form-control" required>
                                    <input type="time" name="hora_final" id="edit_hfin" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    // 2. Inicializar los selectores inteligentes
    document.addEventListener("DOMContentLoaded", function() {
        
        // Aplica el buscador a todos los selects que tengan la clase 'select-search'
        document.querySelectorAll('.select-search').forEach((el) => {
            new TomSelect(el, {
                create: false, // No permite crear opciones que no existan en la BD
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Escriba para buscar..."
            });
        });

    });

    // AUTO DISMISS ALERTAS
    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            let alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 4000);
    });

    // COPIAR ENLACE
    function copiarLink(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert("Enlace de asistencia copiado");
        }).catch(err => console.error('Error:', err));
    }

    // LLENAR MODAL EDITAR
    document.addEventListener("DOMContentLoaded", function() {
        const editBtns = document.querySelectorAll('.btn-editar');
        editBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const d = this.dataset;
                document.getElementById('edit_nombre').value = d.nombre;
                document.getElementById('edit_linea').value = d.linea;
                document.getElementById('edit_programa').value = d.programa ? d.programa : '';
                document.getElementById('edit_sede').value = d.sede;
                document.getElementById('edit_fini').value = d.fini;
                document.getElementById('edit_hini').value = d.hini;
                document.getElementById('edit_ffin').value = d.ffin;
                document.getElementById('edit_hfin').value = d.hfin;
                document.getElementById('formEditarEvento').action = "<?= BASE_URL ?>/dashboard/eventos/editar/" + d.id;
            });
        });
    });
</script>