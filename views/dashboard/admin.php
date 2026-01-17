<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Total Personas</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= number_format($totalUsuarios) ?></h3>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-50">
            <p class="text-xs text-indigo-600 font-semibold flex items-center gap-1">
                <i data-lucide="trending-up" class="w-3 h-3"></i> Base de datos alimentada
            </p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 border-start border-l-4 border-l-cyan-500">
        <div class="flex items-center gap-4">
            <div class="bg-cyan-100 p-3 rounded-xl text-cyan-600">
                <i data-lucide="calendar-days" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Eventos Creados</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= $totalEventos ?></h3>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300">
        <div class="flex items-center gap-4">
            <div class="bg-emerald-100 p-3 rounded-xl text-emerald-600">
                <i data-lucide="award" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm text-slate-500 font-medium">Certificados/Asistencias</p>
                <h3 class="text-2xl font-bold text-slate-800"><?= number_format($totalCertificados) ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white p-8 rounded-2xl border border-slate-100 shadow-sm border-t-4 border-t-indigo-600">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i data-lucide="file-spreadsheet" class="text-emerald-600"></i>
                Carga Masiva de Estudiantes
            </h3>
            <p class="text-sm text-slate-500">Actualiza la base de datos maestra para el nuevo periodo.</p>
        </div>
        <a href="<?= $_ENV['APP_URL'] ?>/assets/plantilla_estudiantes.xlsx" 
           class="text-xs font-bold text-slate-600 hover:text-indigo-600 flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg transition-colors">
            <i data-lucide="download" class="w-4 h-4"></i> Descargar Plantilla
        </a>
    </div>

    <form action="<?= $_ENV['APP_URL'] ?>/admin/importar-usuarios" method="POST" enctype="multipart/form-data" class="space-y-4">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 tracking-wider">Archivo Excel (.xlsx)</label>
                <div class="relative group">
                    <input type="file" name="archivo_excel" accept=".xlsx, .xls" required
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-200 rounded-xl p-1 group-hover:border-indigo-300 transition-colors">
                </div>
            </div>
            <button type="submit" class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                <i data-lucide="upload-cloud" class="w-5 h-5"></i>
                Procesar Base de Datos
            </button>
        </div>
    </form>
</div>