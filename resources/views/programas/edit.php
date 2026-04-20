<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="container px-4 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-warning"><i class="fas fa-pen me-2"></i>Editar Programa</h5>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/dashboard/admin/programas/actualizar/<?= $programa['id_programa'] ?>" method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nombre del Programa</label>
                            <input type="text" name="nombre_programa" class="form-control form-control-lg" value="<?= htmlspecialchars($programa['nombre_programa']) ?>" required style="text-transform: uppercase;">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?= BASE_URL ?>/dashboard/admin/programas" class="btn btn-light border px-4">Cancelar</a>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>