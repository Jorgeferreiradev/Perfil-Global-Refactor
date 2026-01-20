<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="fas fa-users me-2 text-info"></i>Asistentes Registrados</h2>
        <p class="text-muted">Evento: <strong><?= htmlspecialchars($evento['nombre_evento']) ?></strong></p>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/dashboard/eventos" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir Reporte
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if(empty($asistentes)): ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i><br>
                Aún no hay asistentes registrados en este evento.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Fecha/Hora</th>
                            <th>Documento</th>
                            <th>Estudiante</th>
                            <th>Programa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($asistentes as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $row['fecha_asistencia'] ?></td>
                            <td class="fw-bold"><?= $row['numero_documento'] ?></td>
                            <td><?= $row['nombres'] ?> <?= $row['apellidos'] ?></td>
                            <td><small class="text-muted"><?= $row['nombre_programa'] ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3 text-end text-muted">
                Total Asistentes: <strong><?= count($asistentes) ?></strong>
            </div>
        <?php endif; ?>
    </div>
</div>