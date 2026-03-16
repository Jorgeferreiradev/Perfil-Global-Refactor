<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Carga Masiva</h2>
        <p class="text-muted">Actualiza la base de datos de estudiantes y docentes para el periodo actual.</p>
    </div>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success border-0 border-start border-4 border-success shadow-sm">
        <h4 class="alert-heading"><i class="fas fa-check-circle me-2"></i>¡Carga Procesada!</h4>
        <p class="mb-0"><?= htmlspecialchars($_GET['success']) ?></p>
    </div>
<?php endif; ?>

<?php if(isset($_GET['warning'])): 
    $errores = json_decode(urldecode($_GET['warning']), true);
?>
    <div class="alert alert-warning border-0 border-start border-4 border-warning shadow-sm">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Carga con Observaciones</h4>
        <p>El proceso terminó, pero algunas filas fueron omitidas:</p>
        <ul class="mb-0 small" style="max-height: 150px; overflow-y: auto;">
            <?php foreach($errores as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>


<?php if(isset($_SESSION['error_carga'])): ?>
    <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm">
        <h4 class="alert-heading"><i class="fas fa-bomb me-2"></i>Error Crítico</h4>
        <p class="mb-0"><?= $_SESSION['error_carga']; unset($_SESSION['error_carga']); ?></p>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-dark text-white py-3">
                <h6 class="mb-0"><i class="fas fa-file-import me-2"></i>Importar Archivo Maestro</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/dashboard/admin/carga-masiva/procesar" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4 text-center p-5 border-2 border-dashed rounded-3 bg-light">
                        <i class="fas fa-file-excel fa-3x text-success mb-3"></i>
                        <br>
                        <label for="archivo" class="form-label fw-bold cursor-pointer">
                            Arrastra tu archivo aquí o haz clic para buscar
                        </label>
                        <input class="form-control" type="file" id="archivo" name="archivo_excel" accept=".csv, .xlsx, .xls" required>
                        <div class="form-text mt-2">Formatos aceptados: .xlsx, .csv (Máx 10MB)</div>
                    </div>

                    <div class="alert alert-warning d-flex align-items-start small">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Advertencia:</strong> Este proceso puede tardar unos minutos dependiendo del tamaño del archivo. 
                            No cierres la ventana hasta recibir la confirmación.
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-upload me-2"></i>Iniciar Procesamiento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100 bg-primary bg-opacity-10">
            <div class="card-body p-4">
                <h5 class="text-primary fw-bold mb-3">Instrucciones</h5>
                <ol class="list-group list-group-numbered list-group-flush bg-transparent">
                    <li class="list-group-item bg-transparent">Descarga la plantilla oficial institucional.</li>
                    <li class="list-group-item bg-transparent">No modifiques los nombres de las cabeceras.</li>
                    <li class="list-group-item bg-transparent">El campo <strong>Documento</strong> es obligatorio.</li>
                    <li class="list-group-item bg-transparent">Asegúrate de que el periodo académico esté abierto.</li>
                </ol>
                
                <hr class="border-primary opacity-25">
                
                <div class="d-grid mt-4">
                    <a href="<?= BASE_URL ?>/assets/templates/plantilla.xlsx" download="Plantilla_Oficial_FESC.xlsx" class="btn btn-outline-primary bg-green">
                        <i class="fas fa-file-excel me-2"></i>Descargar Plantilla (.xlsx)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>