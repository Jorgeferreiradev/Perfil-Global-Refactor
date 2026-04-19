<?php
include __DIR__ . '/../layouts/header.php'; 
include __DIR__ . '/../layouts/sidebar.php';
?>

<div class="container-fluid px-4 mt-4">
    
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold text-dark">Directorio de Personas</h1>
            <p class="text-muted">Gestión de estudiantes, docentes y administrativos.</p>
        </div>
        
        <?php if (isset($_SESSION['periodo_vista_estado']) && $_SESSION['periodo_vista_estado'] === 'activo'): ?>
            <a href="<?= BASE_URL ?>/dashboard/personas/crear" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Nueva Persona
            </a>
        <?php endif; ?>
    </div>

    <!-- Buscador -->
    <div class="card border-0 shadow-xs mb-4">
        <div class="card-body p-3">
            <form action="" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0" 
                               placeholder="Buscar por cédula, nombre o apellido..." 
                               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100">Buscar</button>
                </div>
                <?php if (!empty($_GET['q'])): ?>
                    <div class="col-md-2">
                        <a href="<?= BASE_URL ?>/dashboard/personas" class="btn btn-outline-secondary w-100">Limpiar</a>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-xs">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nombre Completo</th>
                            <th>Documento</th>
                            <th>Rol / Tipo</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($personas)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-2x mb-3"></i><br>
                                    No se encontraron personas registradas.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($personas as $p): ?>
                                <tr>
                                    <!-- Nombre y programa -->
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            <?= htmlspecialchars($p['nombres'] . ' ' . $p['apellidos']) ?>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-graduation-cap me-1"></i> 
                                            <?= htmlspecialchars($p['programa_actual'] ?? 'Sin programa asignado') ?>
                                            <?php if ($p['id_tipo_persona'] == 1 && !empty($p['nivel_actual'])): ?>
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle ms-2" 
                                                      style="font-size: 0.75em; vertical-align: middle;">
                                                    <?= htmlspecialchars($p['nivel_actual']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </small>
                                    </td>

                                    <!-- Documento -->
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= $p['tipo_documento'] ?></span> 
                                        <?= $p['numero_documento'] ?>
                                    </td>

                                    <!-- Rol / Tipo -->
                                    <td>
                                        <?php 
                                            $badges = [
                                                'Estudiante'     => 'bg-primary',
                                                'Docente'        => 'bg-success',
                                                'Administrativo' => 'bg-info',
                                                'Graduado'       => 'bg-warning text-dark'
                                            ];
                                            $badgeClass = $badges[$p['nombre_tipo']] ?? 'bg-secondary';
                                        ?>
                                        <span class="badge <?= $badgeClass ?> bg-opacity-75">
                                            <?= $p['nombre_tipo'] ?>
                                        </span>
                                    </td>

                                    <!-- Correo -->
                                    <td>
                                        <?= $p['correo_institucional'] ?? '<span class="text-muted">-</span>' ?>
                                    </td>

                                    <!-- Teléfono -->
                                    <td>
                                        <?= !empty($p['telefono']) 
                                            ? htmlspecialchars($p['telefono']) 
                                            : '<span class="text-muted small">Sin registrar</span>' ?>
                                    </td>

                                    <!-- Estado -->
                                    <td>
                                        <?php if ($p['estado_aprobacion'] === 'activo'): ?>
                                            <span class="text-success small">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="text-warning small">
                                                <i class="fas fa-clock"></i> Pendiente
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="text-end pe-4">
                                        <?php if (isset($_SESSION['periodo_vista_estado']) && $_SESSION['periodo_vista_estado'] === 'activo'): ?>
                                            <a href="<?= BASE_URL ?>/dashboard/personas/editar/<?= $p['id'] ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>