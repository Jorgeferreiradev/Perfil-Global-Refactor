<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="fas fa-users-cog me-2 text-primary"></i>Equipo de Trabajo</h2>
        <p class="text-muted">Administra los accesos de Monitores y Administradores.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
        <i class="fas fa-plus me-2"></i>Nuevo Usuario
    </button>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-4 border-success">
        <i class="fas fa-check-circle me-2"></i> Operación realizada con éxito.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'correo_duplicado'): ?>
    <div class="alert alert-danger shadow-sm border-0 border-start border-4 border-danger">
        <i class="fas fa-exclamation-triangle me-2"></i> El correo ingresado ya existe en el sistema.
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary small text-uppercase">
                    <tr>
                        <th class="ps-4 py-3">Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($usuarios)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-user-friends fa-3x mb-3 opacity-25"></i>
                                <br>No hay otros usuarios registrados aún.
                            </td>
                        </tr>
                    <?php else: ?>
                        <tbody>
                            <?php foreach($usuarios as $u): ?>
                                <?php $esActivo = ($u['deleted_at'] == null); ?>
                                
                                <tr class="<?= !$esActivo ? 'table-secondary text-muted' : '' ?>">
                                    <td><?= $u['id'] ?></td>
                                    <td>
                                        <strong><?= $u['nombres'] ?> <?= $u['apellidos'] ?></strong>
                                        <?php if(!$esActivo): ?>
                                            <span class="badge bg-danger ms-2">Inactivo</span>
                                        <?php else: ?>
                                            <span class="badge bg-success ms-2">Activo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $u['correo'] ?></td>
                                    <td><?= ucfirst($u['rol']) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios/editar/<?= $u['id'] ?>" class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <?php if($esActivo): ?>
                                            <a href="<?= BASE_URL ?>/dashboard/admin/usuarios/estado/<?= $u['id'] ?>" 
                                            class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('¿Seguro que deseas DESACTIVAR a este usuario? No podrá iniciar sesión.')"
                                            title="Desactivar acceso">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>/dashboard/admin/usuarios/estado/<?= $u['id'] ?>" 
                                            class="btn btn-outline-success btn-sm" 
                                            title="Reactivar acceso">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
</tbody>
                    <?php endif; ?>
                </tbody>
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
                    <button type="submit" class="btn btn-primary px-4">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>