<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold"><i class="fas fa-user-clock me-2 text-warning"></i>Usuarios Pendientes</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <?php if(empty($pendientes)): ?>
            <p class="text-center text-muted">No hay solicitudes pendientes de aprobación.</p>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Tipo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pendientes as $p): ?>
                        <tr>
                            <td><?= $p['numero_documento'] ?></td>
                            <td><?= $p['nombres'] ?> <?= $p['apellidos'] ?></td>
                            <td><span class="badge bg-info"><?= $p['nombre_tipo'] ?></span></td>
                            <td>
                                <a href="<?= BASE_URL ?>/dashboard/admin/aprobar/<?= $p['id'] ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Aprobar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>