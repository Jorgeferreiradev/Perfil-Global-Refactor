<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
            <i data-lucide="qr-code" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Eventos a Cargo</p>
            <h3 class="text-2xl font-bold"><?= $totalEventos ?></h3>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="bg-amber-100 p-3 rounded-xl text-amber-600">
            <i data-lucide="user-check" class="w-6 h-6"></i>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Asistencias Registradas</p>
            <h3 class="text-2xl font-bold"><?= $totalCertificados ?></h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i data-lucide="zap" class="text-amber-500"></i> Gestión de Eventos Actuales
        </h3>
        <p class="text-slate-500 text-sm mb-6">Selecciona un evento activo para generar el QR o registrar asistencia manual.</p>
        
        <div class="overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Evento</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-100">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-700">Taller de Ingeniería</td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs"><span class="px-2 py-1 bg-green-100 text-green-700 rounded-full font-bold uppercase">Activo</span></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <button class="text-indigo-600 hover:text-indigo-900 font-bold text-xs uppercase tracking-tighter">Generar QR</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-indigo-600 p-8 rounded-2xl text-white shadow-lg shadow-indigo-200">
        <h4 class="font-bold text-lg mb-2">¿Necesitas ayuda?</h4>
        <p class="text-indigo-100 text-sm mb-6">Si un estudiante no aparece en la carga masiva, usa el registro de "Invitado".</p>
        <button class="w-full bg-white text-indigo-600 font-bold py-3 rounded-xl shadow-sm hover:bg-indigo-50 transition-colors flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i> Registro Manual
        </button>
    </div>
</div>