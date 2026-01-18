<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="fas fa-file-upload me-2"></i>Carga Masiva de Usuarios</h2>
</div>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success border-success">
        <h4 class="alert-heading"><i class="fas fa-check-circle me-2"></i>¡Carga Exitosa!</h4>
        <p><?= htmlspecialchars($_GET['success']) ?></p>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger border-danger">
        <h4 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Error Crítico</h4>
        <p><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></p>
    </div>
<?php endif; ?>

<?php if(isset($_GET['error']) && $_GET['error'] == 'formato_incorrecto'): ?>
    <div class="alert alert-warning">Por favor sube un archivo con extensión .xlsx, .xls o .csv</div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-dark text-white">
                Subir Base de Datos Maestra
            </div>
            <div class="card-body p-4">
                <form action="/dashboard/admin/carga-masiva/procesar" method="POST" enctype="multipart/form-data">
                    
                    <div class="mb-4">
                        <label for="archivo" class="form-label fw-bold">Selecciona el archivo Excel (.xlsx)</label>
                        <input class="form-control form-control-lg" type="file" id="archivo" name="archivo_excel" accept=".xlsx, .xls, .csv" required>
                        <div class="form-text text-muted">
                            Asegúrate de que el archivo siga estrictamente la plantilla institucional.
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-info-circle fa-2x me-3"></i>
                        <div>
                            <strong>Nota Importante:</strong><br>
                            Este proceso actualizará los datos de estudiantes existentes y creará los nuevos. 
                            Asegúrate de haber cerrado el ciclo anterior antes de cargar el nuevo semestre.
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Procesar Archivo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h5 class="card-title text-muted"><i class="fas fa-download me-2"></i>Recursos</h5>
                <p class="card-text small">Descarga la plantilla oficial para evitar errores de formato en la carga.</p>
                <a href="/downloads/Plantilla_Importar_ComunidadAcademica.xlsx" class="btn btn-outline-success w-100 mb-2">
                    <i class="fas fa-file-excel me-2"></i>Descargar Plantilla
                </a>
            </div>
        </div>
    </div>
</div>