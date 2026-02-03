<div class="container-fluid px-4 mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Editar Usuario</h1>
        <a href="<?= BASE_URL ?>/dashboard/admin/usuarios" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Volver
        </a>
    </div>

    <?php if(isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
            <?= $_SESSION['flash']['msg'] ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Datos del Usuario</h6>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>/dashboard/admin/usuarios/actualizar/<?= $usuario['id'] ?>" method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="nombres" class="form-control" value="<?= $usuario['nombres'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="<?= $usuario['apellidos'] ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="correo" class="form-control" value="<?= $usuario['correo'] ?>" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rol de Sistema <span class="text-danger">*</span></label>
                        <select name="rol" class="form-select" required>
                            <option value="monitor" <?= $usuario['rol'] == 'monitor' ? 'selected' : '' ?>>Monitor</option>
                            <option value="admin" <?= $usuario['rol'] == 'admin' ? 'selected' : '' ?>>Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-primary fw-bold">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Dejar en blanco para mantener la actual">
                    <small class="text-muted">Solo escribe aquí si deseas cambiarle la clave al usuario.</small>
                </div>

                <hr>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>