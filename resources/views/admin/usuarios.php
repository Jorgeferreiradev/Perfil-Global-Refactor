<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-users-cog me-2"></i>Gestión de Usuarios</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
        <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
    </button>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        Operación realizada con éxito.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if(isset($_GET['error']) && $_GET['error'] == 'correo_duplicado'): ?>
    <div class="alert alert-danger">
        El correo ingresado ya existe en el sistema.
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($usuarios as $usr): ?>
                <tr>
                    <td>
                        <div class="fw-bold"><?= htmlspecialchars($usr['nombres'] . ' ' . $usr['apellidos']) ?></div>
                    </td>
                    <td><?= htmlspecialchars($usr['correo']) ?></td>
                    <td>
                        <?php 
                        $badges = [
                            'admin' => 'bg-danger',
                            'monitor' => 'bg-info text-dark',
                            'dev' => 'bg-warning text-dark'
                        ];
                        $badgeClass = $badges[$usr['rol']] ?? 'bg-secondary';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= strtoupper($usr['rol']) ?></span>
                    </td>
                    <td class="text-end">
                        <a href="/dashboard/admin/usuarios/eliminar/<?= $usr['id'] ?>" 
                           class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('¿Estás seguro? Este usuario perderá acceso inmediato.');">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if(empty($usuarios)): ?>
            <div class="p-4 text-center text-muted">No hay otros usuarios registrados.</div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalNuevoUsuario" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/dashboard/admin/usuarios/guardar" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Registrar Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo Institucional</label>
                        <input type="email" name="correo" class="form-control" required placeholder="@fesc.edu.co">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rol del Sistema</label>
                        <select name="rol" class="form-select" required>
                            <option value="monitor">Monitor (Gestión de Eventos)</option>
                            <option value="admin">Administrador (Control Total)</option>
                            <option value="dev">Desarrollador (Modo Sandbox)</option>
                        </select>
                        <div class="form-text small text-muted">
                            <i class="fas fa-info-circle"></i> El rol <strong>DEV</strong> permite crear eventos de prueba que no afectan las estadísticas.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña Inicial</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>