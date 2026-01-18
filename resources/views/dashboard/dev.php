<div class="mb-8 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-2xl shadow-sm flex justify-between items-center">
    <div class="flex items-center gap-3">
        <i data-lucide="alert-triangle" class="text-amber-600 w-6 h-6"></i>
        <div>
            <h4 class="text-amber-800 font-bold text-sm uppercase tracking-wider">Modo Desarrollador (Sandbox)</h4>
            <p class="text-amber-700 text-xs">Todos los datos creados se marcarán con <code class="bg-amber-200 px-1 rounded font-bold">es_simulacion = 1</code>.</p>
        </div>
    </div>
    <a href="<?= $_ENV['APP_URL'] ?>/dev/clean-manual" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold py-2 px-4 rounded-xl transition-all flex items-center gap-2">
        <i data-lucide="trash-2" class="w-4 h-4"></i> Limpiar BD Simulación
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 opacity-80 pointer-events-none">
    <div class="bg-white p-6 rounded-2xl border border-slate-200">
        <p class="text-xs text-slate-400 font-bold mb-1">USUARIOS REALES</p>
        <h3 class="text-3xl font-black"><?= $totalUsuarios ?></h3>
    </div>
    </div>

<div class="mt-8 bg-slate-900 text-slate-300 p-8 rounded-2xl border border-slate-700">
    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
        <i data-lucide="terminal" class="text-indigo-400"></i> Consola de Pruebas V2
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-4 bg-slate-800 rounded-xl border border-slate-700">
            <p class="text-xs font-bold mb-3 uppercase tracking-widest text-slate-500">Sesión y Middleware</p>
            <div class="space-y-2">
                <p class="text-sm font-mono flex justify-between"><span>Session ID:</span> <span class="text-indigo-400"><?= session_id() ?></span></p>
                <p class="text-sm font-mono flex justify-between"><span>Last Activity:</span> <span class="text-indigo-400"><?= date('H:i:s', $_SESSION['last_activity']) ?></span></p>
            </div>
        </div>
        
        <div class="p-4 bg-slate-800 rounded-xl border border-slate-700">
            <p class="text-xs font-bold mb-3 uppercase tracking-widest text-slate-500">Flags de Base de Datos</p>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 bg-indigo-500 rounded-full animate-pulse"></span>
                <span class="text-sm font-mono text-indigo-300">Auto-tagging: ON (es_simulacion)</span>
            </div>
        </div>
    </div>
</div>