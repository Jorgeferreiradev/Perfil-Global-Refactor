<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container px-4 mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <?php if(isset($_SESSION['flash'])): ?>
                <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show">
                    <?= $_SESSION['flash']['msg'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0 text-primary"><i class="fas fa-user-plus me-2"></i>Nueva Persona</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/dashboard/personas/guardar" method="POST">
                        
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label fw-bold">Tipo de Vinculación <span class="text-danger">*</span></label>
                                <select name="id_tipo_persona" class="form-select" required>
                                    <option value="">Seleccione...</option>
                                    <?php foreach($tipos as $t): ?>
                                        <option value="<?= $t['id_tipo'] ?>"><?= $t['nombre_tipo'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo Doc.</label>
                                <select name="tipo_documento" class="form-select">
                                    <option value="CC">Cédula (CC)</option>
                                    <option value="TI">Tarjeta Identidad (TI)</option>
                                    <option value="CE">Cédula Extranjería (CE)</option>
                                    <option value="PPT">PPT (Permiso P. Temporal)</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                <input type="number" name="numero_documento" class="form-control" required placeholder="Ej: 1090...">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" name="nombres" class="form-control" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" name="apellidos" class="form-control" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Institucional</label>
                                <input type="email" name="correo_institucional" class="form-control" placeholder="@fesc.edu.co">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono / Celular</label>
                                <input type="text" name="telefono" class="form-control">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= BASE_URL ?>/dashboard/personas" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>Guardar Persona</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>