<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Registro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        body { background-color: #fff8e1; }
        .card-manual {
            max-width: 500px;
            margin: 30px auto;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .header-manual {
            background: #ffc107;
            color: #000;
            border-radius: 20px 20px 0 0;
            padding: 20px;
            text-align: center;
        }
        .fade-out {
            opacity: 0;
            transition: opacity 1s ease-out;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <?php if (isset($_GET['error'])): ?>
            <div id="alerta-flotante" class="alert alert-danger text-center shadow fixed-top m-3" style="z-index: 1050;">
                <i class="fas fa-exclamation-circle me-2"></i> <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>

        <div class="card card-manual border-0">
            <div class="header-manual">
                <h4 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i>Usuario Nuevo</h4>
                <p class="small mb-0 mt-1 opacity-75">Completa tus datos para ingresar</p>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-light border text-center small text-muted mb-4">
                    El documento <strong><?= htmlspecialchars($documento) ?></strong> no fue encontrado.
                </div>

                <form id="formManual" action="<?= BASE_URL ?>/asistencia/guardar-manual" method="POST">
                    
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <input type="hidden" name="documento" value="<?= $documento ?>">

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tipo Documento</label>
                            <select name="tipo_documento" class="form-select" required>
                                <option value="CC">Cédula (CC)</option>
                                <option value="TI">Tarjeta Identidad</option>
                                <option value="PPT">PPT</option>
                                <option value="CE">Extranjería</option>
                                <option value="PASAPORTE">Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Teléfono / Celular</label>
                            <input type="text" name="telefono" class="form-control" 
                                value="<?= $persona['telefono'] ?? '' ?>" 
                                placeholder="Ej: +57 313..." 
                                oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                        </div>

                        <hr>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Nombres</label>
                            <input type="text" name="nombres" class="form-control" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Apellidos</label>
                            <input type="text" name="apellidos" class="form-control" required style="text-transform: uppercase;" oninput="this.value = this.value.toUpperCase()">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Correo Electrónico</label>
                        <input type="email" name="correo" class="form-control" placeholder="ejemplo@fesc.edu.co" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Programa Universitario</label>
                        <select name="id_programa" id="select-programa" class="form-select" required placeholder="Escribe para buscar tu carrera...">
                            <option value="">Buscar carrera...</option>
                            <?php if(!empty($programas)): ?>
                                <?php foreach($programas as $prog): ?>
                                    <option value="<?= $prog['id_programa'] ?>">
                                        <?= htmlspecialchars($prog['nombre_programa']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="99">Invitado / Externo</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Tipo de Vinculación</label>
                        <select name="id_tipo" class="form-select" required>
                            <option value="" selected disabled>Seleccione una opción...</option>
                            <option value="99" class="fw-bold">🌟 Invitado Externo</option>
                            <option value="1">🎓 Estudiante</option>
                            <option value="4">🧑‍🎓 Graduado</option>
                            <option value="2">👨‍🏫 Docente</option>
                            <option value="3">💼 Administrativo</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Algunos perfiles requieren verificación previa antes de habilitar el acceso.
                        </small>

                        <button type="submit" class="btn btn-warning fw-bold text-dark" id="btnGuardar">
                            Registrar y entrar
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            
            // Inicializar el buscador de carreras en tiempo real
            new TomSelect("#select-programa", {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: 'Escribe para buscar...',
                maxOptions: null // Mostrar todos los resultados que coincidan
            });

            // 1. LIMPIEZA AL VOLVER ATRÁS
            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    document.getElementById("formManual").reset();
                    document.getElementById("btnGuardar").disabled = false;
                }
            });

            // 2. DESVANECER ALERTAS
            const alerta = document.getElementById('alerta-flotante');
            if (alerta) {
                setTimeout(() => {
                    alerta.classList.add('fade-out');
                    setTimeout(() => { alerta.remove(); }, 1000);
                }, 5000);
            }
        });
    </script>
</body>
</html>