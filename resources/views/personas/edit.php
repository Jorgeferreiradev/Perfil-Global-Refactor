<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>

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
                    <h5 class="fw-bold mb-0 text-warning"><i class="fas fa-user-edit me-2"></i>Editar Persona</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/dashboard/personas/actualizar/<?= $persona['id'] ?>" method="POST">
                        
                        <div class="row g-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Tipo de Vinculación</label>
                                <select name="id_tipo_persona" class="form-select" required>
                                    <?php foreach($tipos as $t): ?>
                                        <option value="<?= $t['id_tipo'] ?>" <?= $persona['id_tipo_persona'] == $t['id_tipo'] ? 'selected' : '' ?>>
                                            <?= $t['nombre_tipo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label fw-bold">Carrera / Programa</label>
                                <select name="id_programa" id="select-programa-admin" class="form-select" required placeholder="Escribe para buscar carrera...">                                    <option value="">Seleccione Carrera...</option>
                                    <?php foreach($programas as $prog): ?>
                                        <option value="<?= $prog['id_programa'] ?>" <?= (isset($persona['id_programa']) && $persona['id_programa'] == $prog['id_programa']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($prog['nombre_programa']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo Doc.</label>
                                <select name="tipo_documento" class="form-select">
                                    <option value="CC" <?= $persona['tipo_documento'] == 'CC' ? 'selected' : '' ?>>Cédula (CC)</option>
                                    <option value="TI" <?= $persona['tipo_documento'] == 'TI' ? 'selected' : '' ?>>Tarjeta Identidad</option>
                                    <option value="PPT" <?= $persona['tipo_documento'] == 'PPT' ? 'selected' : '' ?>>PPT</option>
                                    <option value="CE" <?= $persona['tipo_documento'] == 'CE' ? 'selected' : '' ?>>Extranjería</option>
                                    <option value="Pasaporte" <?= $persona['tipo_documento'] == 'Pasaporte' ?'seledted' :'' ?>>Pasaporte</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Número de Documento <span class="text-danger">*</span></label>
                                <input type="text" name="numero_documento" class="form-control" 
                                    value="<?= $persona['numero_documento'] ?? '' ?>" required 
                                    placeholder="Ej: 1090..." 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nombres</label>
                                <input type="text" name="nombres" class="form-control" value="<?= $persona['nombres'] ?>" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" value="<?= $persona['apellidos'] ?>" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Correo Institucional</label>
                                <input type="email" name="correo_institucional" class="form-control" value="<?= $persona['correo_institucional'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono / Celular</label>
                                <input type="text" name="telefono" class="form-control" 
                                    value="<?= $persona['telefono'] ?? '' ?>" 
                                    placeholder="Ej: +57 313..." 
                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmarEliminar(<?= $persona['id'] ?>)">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>

                            <div class="d-flex gap-2">
                                <a href="<?= BASE_URL ?>/dashboard/personas" class="btn btn-light border">Cancelar</a>
                                <button type="submit" class="btn btn-warning px-4"><i class="fas fa-save me-2"></i>Actualizar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarEliminar(id) {
    if(confirm('¿Estás seguro de eliminar a esta persona?')) {
        window.location.href = '<?= BASE_URL ?>/dashboard/personas/eliminar/' + id;
    }
}
</script>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        new TomSelect("#select-programa-admin", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>