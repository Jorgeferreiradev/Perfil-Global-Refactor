<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="fas fa-users-cog me-2 text-primary"></i>Equipo de Trabajo</h2>
        <p class="text-muted">Administra los accesos de Monitores y Administradores.</p>
    </div>
    <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
        <i class="fas fa-plus me-2"></i>Nuevo Usuario
    </button>
</div>

<?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show shadow-sm border-0 border-start border-4 border-<?= $_SESSION['flash']['type'] ?>">
        <?= $_SESSION['flash']['msg'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-5">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Usuario</th>
                        <th>Rol</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                
                <?php if(empty($usuarios)): ?>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-user-friends fa-3x mb-3 opacity-25"></i>
                                <br>No hay otros usuarios registrados aún.
                            </td>
                        </tr>
                    </tbody>
                <?php else: ?>
                    <tbody>
                        <?php foreach($usuarios as $u): ?>
                            <?php $esActivo = ($u['deleted_at'] === null); ?>
                            
                            <tr class="<?= !$esActivo ? 'table-secondary text-muted' : '' ?>">
                                
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width:38px;height:38px;font-size:15px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($u['correo']) ?></div>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <?php if($u['rol'] === 'superadmin'): ?>
                                        <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3">
                                            <i class="fas fa-crown me-1"></i>SuperAdmin
                                        </span>
                                    <?php elseif($u['rol'] === 'admin'): ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">
                                            <i class="fas fa-shield-alt me-1"></i>Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                            <i class="fas fa-user-check me-1"></i>Monitor
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-center">
                                    <?php if($esActivo): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Inactivo</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end pe-4">
                                    
                                    <a href="<?= BASE_URL ?>/dashboard/admin/usuarios/editar/<?= $u['id'] ?>" 
                                       class="btn btn-sm btn-light text-primary border shadow-sm me-1" title="Editar">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <?php if($esActivo): ?>
                                        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios/estado/<?= $u['id'] ?>" 
                                           class="btn btn-sm btn-outline-danger shadow-sm" 
                                           title="Desactivar Usuario"
                                           onclick="return confirm('¿Seguro que deseas desactivar a este usuario? Perderá el acceso al sistema.')">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios/estado/<?= $u['id'] ?>" 
                                           class="btn btn-sm btn-success shadow-sm" 
                                           title="Reactivar Usuario"
                                           onclick="return confirm('¿Seguro que deseas volver a habilitar a este usuario?')">
                                            <i class="fas fa-check-circle me-1"></i> Reactivar
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Nuevo Usuario</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= BASE_URL ?>/dashboard/admin/usuarios/guardar" method="POST">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Correo Institucional</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="correo" class="form-control" placeholder="usuario@fesc.edu.co" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Rol en el Sistema</label>
                            <select name="rol" class="form-select" required>
                                <option value="monitor" selected>🔵 Monitor (Operativo)</option>
                                <option value="admin">🔴 Administrador (Total)</option>
                            </select>
                            <div class="form-text small bg-light p-2 rounded mt-2 border">
                                <i class="fas fa-info-circle text-primary"></i> 
                                <strong>El Monitor</strong> solo puede gestionar Eventos y Asistencia.
                                <br>
                                <strong>El Admin</strong> Acceso total: Carga Masiva y Gestión de Usuarios.
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Contraseña Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control" required minlength="6" placeholder="Mínimo 6 caracteres">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>