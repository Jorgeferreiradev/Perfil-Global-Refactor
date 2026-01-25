<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold text-primary"><i class="fas fa-user-clock me-2"></i>Solicitudes Pendientes</h2>
</div>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success fade-out">
        <?= $_GET['msg'] == 'aprobado' ? '✅ Usuario aprobado y activado.' : '🗑️ Solicitud rechazada y eliminada.' ?>
    </div>
<?php endif; ?>

<div class="card shadow border-0">
    <div class="card-body">
        
        <?php if (empty($pendientes)): ?>
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-3 opacity-50"></i>
                <h4 class="text-muted">¡Todo al día!</h4>
                <p class="text-secondary">No hay solicitudes pendientes por revisar.</p>
            </div>
        <?php else: ?>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Documento</th>
                            <th>Nombre Completo</th>
                            <th>Tipo</th>
                            <th>Correo</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($pendientes as $p): ?>
                            <tr>
                                <td class="fw-bold"><?= $p['numero_documento'] ?></td>
                                <td><?= $p['nombres'] ?> <?= $p['apellidos'] ?></td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        <?= $p['nombre_tipo'] ?>
                                    </span>
                                </td>
                                <td class="small text-muted"><?= $p['correo_institucional'] ?></td>
                                <td class="text-end">
                                    <a href="<?= BASE_URL ?>/dashboard/admin/aprobar/<?= $p['id'] ?>" 
                                       class="btn btn-success btn-sm me-1"
                                       title="Aprobar ingreso">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    
                                    <a href="<?= BASE_URL ?>/dashboard/admin/rechazar/<?= $p['id'] ?>" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('¿Estás seguro de rechazar y eliminar esta solicitud?');"
                                       title="Rechazar solicitud">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<style>.fade-out { animation: fadeOut 1s forwards; animation-delay: 3s; } @keyframes fadeOut { to { opacity: 0; visibility: hidden; } }</style>