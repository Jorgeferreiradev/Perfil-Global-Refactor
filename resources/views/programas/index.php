<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php include __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="container-fluid px-4 mt-4 mb-5">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-dark mb-0">
            <i class="fas fa-graduation-cap me-2 text-primary"></i>Programas Académicos
        </h3>
        <div class="d-flex gap-2">
            <button onclick="descargarCSV('Plantilla_Programas_FESC.csv')" class="btn btn-success fw-bold shadow-sm">
                <i class="fas fa-file-csv me-1"></i> Descargar Excel/CSV
            </button>
            <a href="<?= BASE_URL ?>/dashboard/admin/programas/crear" class="btn btn-primary fw-bold shadow-sm">
                <i class="fas fa-plus me-1"></i> Nuevo Programa
            </a>
        </div>
    </div>

    <?php if(isset($_SESSION['flash'])): ?>
        <div id="alerta-sistema" class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible shadow-sm position-fixed top-0 start-50 translate-middle-x mt-3" style="z-index: 1050; min-width: 300px;">
            <i class="fas <?= $_SESSION['flash']['type'] === 'success' ? 'fa-check-circle' : 'fa-info-circle' ?> me-2"></i>
            <?= $_SESSION['flash']['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="buscadorTabla" class="form-control border-start-0 ps-0" placeholder="Escribe para filtrar programas en tiempo real...">
            </div>
            <small class="text-muted ms-auto d-none d-md-block"><i class="fas fa-info-circle me-1"></i>Haz clic en los encabezados para ordenar</small>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tablaProgramas">
                    <thead class="bg-light text-uppercase small text-muted">
                        <tr>
                            <th class="px-4 py-3 th-sortable" style="cursor: pointer;" title="Ordenar por ID">
                                ID <i class="fas fa-sort ms-1 opacity-50"></i>
                            </th>
                            <th class="py-3 th-sortable" style="cursor: pointer;" title="Ordenar por Nombre">
                                Nombre del Programa <i class="fas fa-sort ms-1 opacity-50"></i>
                            </th>
                            <th class="py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($programas as $p): ?>
                        <tr class="fila-programa">
                            <td class="px-4 fw-bold text-muted" data-id="<?= $p['id_programa'] ?>">#<?= $p['id_programa'] ?></td>
                            <td class="fw-bold nombre-celda"><?= htmlspecialchars($p['nombre_programa']) ?></td>
                            <td class="text-center">
                                <?php if($p['estado'] === 'activo'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 text-end">
                                <a href="<?= BASE_URL ?>/dashboard/admin/programas/editar/<?= $p['id_programa'] ?>" class="btn btn-sm btn-light text-primary border me-1" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="<?= BASE_URL ?>/dashboard/admin/programas/estado/<?= $p['id_programa'] ?>" class="btn btn-sm <?= $p['estado'] === 'activo' ? 'btn-outline-danger' : 'btn-outline-success' ?>" title="<?= $p['estado'] === 'activo' ? 'Desactivar' : 'Activar' ?>">
                                    <i class="fas <?= $p['estado'] === 'activo' ? 'fa-ban' : 'fa-check' ?>"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // 1. DESAPARECER ALERTA EN 4 SEGUNDOS
    const alerta = document.getElementById('alerta-sistema');
    if (alerta) {
        setTimeout(() => {
            alerta.style.transition = "opacity 0.8s ease-out, transform 0.8s ease-out";
            alerta.style.opacity = "0";
            alerta.style.transform = "translate(-50%, -20px)"; // Sube un poco al desaparecer
            setTimeout(() => alerta.remove(), 800); // Lo borra del DOM tras la animación
        }, 4000);
    }

    // 2. FILTRO EN TIEMPO REAL
    const buscador = document.getElementById('buscadorTabla');
    buscador.addEventListener('keyup', function() {
        const texto = this.value.toLowerCase();
        const filas = document.querySelectorAll('.fila-programa');
        
        filas.forEach(fila => {
            // Busca en toda la fila (ID y Nombre)
            const contenido = fila.textContent.toLowerCase();
            fila.style.display = contenido.includes(texto) ? '' : 'none';
        });
    });

    // 3. ORDENAMIENTO DE COLUMNAS (ID y Nombre)
    const headers = document.querySelectorAll('th.th-sortable');
    headers.forEach(header => {
        header.addEventListener('click', function() {
            const table = header.closest('table');
            const tbody = table.querySelector('tbody');
            const index = Array.from(header.parentNode.children).indexOf(header);
            const asc = header.classList.toggle('asc'); // Alterna entre ascendente y descendente
            
            // Efecto visual en las flechas
            headers.forEach(h => h.querySelector('.fas').className = 'fas fa-sort ms-1 opacity-50');
            header.querySelector('.fas').className = asc ? 'fas fa-sort-up ms-1 text-primary' : 'fas fa-sort-down ms-1 text-primary';

            Array.from(tbody.querySelectorAll('tr'))
                .sort((a, b) => {
                    let valA = a.children[index].textContent.trim();
                    let valB = b.children[index].textContent.trim();
                    
                    // Si es la columna de ID, quitamos el '#' y convertimos a número
                    if (index === 0) {
                        valA = parseInt(valA.replace('#', ''));
                        valB = parseInt(valB.replace('#', ''));
                        return asc ? valA - valB : valB - valA;
                    }
                    
                    // Si es texto (Nombre), ordenamos alfabéticamente
                    return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
                })
                .forEach(tr => tbody.appendChild(tr)); // Reordena en el DOM
        });
    });
});

// 4. DESCARGAR CSV (Para la Carga Masiva)
function descargarCSV(filename) {
    const filas = document.querySelectorAll("#tablaProgramas tr");
    let csv = [];
    
    for (let i = 0; i < filas.length; i++) {
        let fila = [], columnas = filas[i].querySelectorAll("td, th");
        
        // Excluimos la última columna ("Acciones") que no sirve para el Excel
        for (let j = 0; j < columnas.length - 1; j++) {
            // Limpiamos los saltos de línea y quitamos el '#' del ID para que el Excel lo lea como número puro
            let textoCelda = columnas[j].innerText.replace(/(\r\n|\n|\r)/gm, "").trim();
            if(j === 0) textoCelda = textoCelda.replace('#', ''); 
            
            fila.push('"' + textoCelda + '"');
        }
        csv.push(fila.join(","));
    }
    
    // Crear y descargar el archivo
    const csvFile = new Blob(["\uFEFF" + csv.join("\n")], {type: "text/csv;charset=utf-8;"}); // \uFEFF para que Excel lea los acentos (UTF-8)
    const downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>