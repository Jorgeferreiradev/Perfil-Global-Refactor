<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto border-0" style="max-width: 500px;">
            <div class="card-header bg-warning text-dark fw-bold text-center">
                <i class="fas fa-user-plus me-2"></i>Usuario no encontrado
            </div>
            <div class="card-body p-4">
                <p class="small text-muted text-center mb-4">
                    El documento <strong><?= htmlspecialchars($documento) ?></strong> no aparece en la Base de Datos Maestra.<br>
                    Por favor, completa tus datos para ingresar.
                </p>
                
                <form action="<?= BASE_URL ?>/asistencia/guardar-manual" method="POST">
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <input type="hidden" name="documento" value="<?= $documento ?>">

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small fw-bold">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Correo</label>
                        <input type="email" name="correo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tipo de Persona</label>
                        <select name="id_tipo" class="form-select" required>
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="5">Invitado Externo (Acceso Inmediato)</option>
                            <option value="1">Estudiante (Requiere Aprobación)</option>
                            <option value="2">Docente (Requiere Aprobación)</option>
                            <option value="3">Administrativo (Requiere Aprobación)</option>
                            <option value="4">Egresado (Requiere Aprobación)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold">Confirmar y Registrar</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>