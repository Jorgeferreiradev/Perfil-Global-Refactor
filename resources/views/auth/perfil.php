<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <?php if(isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show">
                    <?= $_SESSION['flash']['msg'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <div class="card border-0 shadow-lg">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit me-2"></i>Mi Perfil</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/perfil/actualizar" method="POST">
                        
                        <div class="text-center mb-4">
                            <div class="icon-box bg-light rounded-circle mx-auto d-flex align-items-center justify-content-center text-primary" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-3x"></i>
                            </div>
                            <h5 class="mt-3 fw-bold"><?= $usuario['nombres'] ?> <?= $usuario['apellidos'] ?></h5>
                            <span class="badge bg-secondary"><?= strtoupper($usuario['rol']) ?></span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombres</label>
                                <input type="text" name="nombres" class="form-control" value="<?= $usuario['nombres'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="<?= $usuario['apellidos'] ?>" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control bg-light" value="<?= $usuario['correo'] ?>" readonly disabled>
                                <small class="text-muted">El correo no se puede cambiar por seguridad.</small>
                            </div>

                            <div class="col-12 mt-4">
                                <hr>
                                <h6 class="text-primary fw-bold"><i class="fas fa-lock me-2"></i>Cambiar Contraseña</h6>
                                <small class="text-muted d-block mb-3">Deja estos campos vacíos si no quieres cambiar tu clave.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="******">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirmar Contraseña</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="******">
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>