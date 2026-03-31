<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg h-100 bg-primary text-white">
            <div class="card-body p-4 text-center">
                <i class="fas fa-layer-group fa-3x mb-3"></i>
                <h3 class="fw-bold">Matriz General Semestral</h3>
                <p class="opacity-75 mb-4">
                    Consolidado de todas las Líneas de Acción con cálculo de <strong>Beneficiarios Reales</strong> (sin duplicados).
                </p>
                
                <form action="<?= BASE_URL ?>/dashboard/reportes/descargar-matriz" method="POST">
                    
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-white"><i class="fas fa-map-marker-alt me-1"></i> Filtrar por Sede:</label>
                        <select name="sede" class="form-select border-0 shadow-sm text-dark">
                            <option value="Todas">Todas las Sedes (Consolidado Total)</option>
                            <option value="Cúcuta">Sede Cúcuta</option>
                            <option value="Ocaña">Sede Ocaña</option>
                            <option value="Virtual">Sede Virtual</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-light fw-bold w-100 py-2 text-primary">
                        <i class="fas fa-download me-2"></i> Descargar Excel
                    </button>
                    <button type="submit" class="btn btn-light fw-bold w-100 py-2 text-primary mt-2" formaction="<?= BASE_URL ?>/dashboard/reportes/descargar-matriz-pdf">
                        <i class="fas fa-file-pdf me-2"></i> Descargar PDF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold text-dark mb-0">Reporte Individual por Evento</h5>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Filtre, busque y seleccione el evento a descargar.</p>
                
                <form action="<?= BASE_URL ?>/dashboard/reportes/descargar-individual" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-primary">1. Filtrar por Sede:</label>
                        <select id="filtro_sede_eventos" class="form-select border-primary bg-primary bg-opacity-10">
                            <option value="Todas" selected>Mostrar todos los eventos</option>
                            <option value="Cúcuta">Solo eventos Cúcuta</option>
                            <option value="Ocaña">Solo eventos Ocaña</option>
                            <option value="Virtual">Solo eventos Virtuales</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold">2. Buscar y Seleccionar:</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white text-muted border-secondary border-end-0">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="buscador_texto" class="form-control border-secondary border-start-0" placeholder="Escriba palabras clave (ej. Taller, Inducción)...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <select name="id_evento" id="select_eventos" class="form-select border-secondary shadow-sm" required size="6">
                            
                            <?php 
                            $eventosCucuta = array_filter($listaEventos, fn($e) => $e['sede'] == 'Cúcuta');
                            $eventosOcana = array_filter($listaEventos, fn($e) => $e['sede'] == 'Ocaña');
                            $eventosVirtual = array_filter($listaEventos, fn($e) => $e['sede'] == 'Virtual');
                            ?>

                            <?php if (!empty($eventosCucuta)): ?>
                                <optgroup label="📍 SEDE CÚCUTA" class="text-primary bg-light">
                                    <?php foreach($eventosCucuta as $ev): ?>
                                        <option value="<?= $ev['id_evento'] ?>" data-sede="Cúcuta" class="py-1">
                                            <?= date('d/m/Y', strtotime($ev['fecha_inicio'])) ?> - <?= htmlspecialchars($ev['nombre_evento']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>

                            <?php if (!empty($eventosOcana)): ?>
                                <optgroup label="📍 SEDE OCAÑA" class="text-primary bg-light">
                                    <?php foreach($eventosOcana as $ev): ?>
                                        <option value="<?= $ev['id_evento'] ?>" data-sede="Ocaña" class="py-1">
                                            <?= date('d/m/Y', strtotime($ev['fecha_inicio'])) ?> - <?= htmlspecialchars($ev['nombre_evento']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>

                            <?php if (!empty($eventosVirtual)): ?>
                                <optgroup label="💻 SEDE VIRTUAL" class="text-primary bg-light">
                                    <?php foreach($eventosVirtual as $ev): ?>
                                        <option value="<?= $ev['id_evento'] ?>" data-sede="Virtual" class="py-1">
                                            <?= date('d/m/Y', strtotime($ev['fecha_inicio'])) ?> - <?= htmlspecialchars($ev['nombre_evento']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>

                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-outline-dark">
                            <i class="fas fa-file-export me-2"></i> Generar Excel
                        </button>
                        <button type="submit" class="btn btn-outline-dark mt-2" formaction="<?= BASE_URL ?>/dashboard/reportes/descargar-individual-pdf">
                            <i class="fas fa-file-pdf me-2"></i> Generar PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> <script>
document.addEventListener("DOMContentLoaded", function() {
    const filtroSede = document.getElementById('filtro_sede_eventos');
    const buscadorTexto = document.getElementById('buscador_texto');
    const selectEventos = document.getElementById('select_eventos');
    const optGroups = selectEventos.getElementsByTagName('optgroup');
    
    // Guardamos las opciones para poder filtrarlas rápido
    const opcionesOriginales = Array.from(selectEventos.options);

    function aplicarFiltros() {
        const sedeElegida = filtroSede.value;
        const textoBuscado = buscadorTexto.value.toLowerCase().trim();
        
        // 1. Filtrar las opciones individuales
        opcionesOriginales.forEach(opcion => {
            const coincideSede = (sedeElegida === "Todas" || opcion.getAttribute('data-sede') === sedeElegida);
            const coincideTexto = opcion.text.toLowerCase().includes(textoBuscado);
            
            // Mostrar solo si cumple AMBAS condiciones (Sede y Texto)
            opcion.style.display = (coincideSede && coincideTexto) ? "" : "none";
        });

        // 2. Lógica Senior: Ocultar los títulos de sede (optgroups) si quedaron vacíos por la búsqueda
        Array.from(optGroups).forEach(grupo => {
            const tieneVisibles = Array.from(grupo.children).some(op => op.style.display !== "none");
            grupo.style.display = tieneVisibles ? "" : "none";
        });
    }

    // Escuchar cambios en el selector de sede y cuando se escribe en el buscador
    filtroSede.addEventListener('change', aplicarFiltros);
    buscadorTexto.addEventListener('input', aplicarFiltros);
});
</script>