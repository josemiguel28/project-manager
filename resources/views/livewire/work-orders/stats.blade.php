<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">

    <div class="rounded-lg border border-slate-200 border-l-4 border-l-amber-500 bg-white p-5 shadow-sm">
        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Cobrado este mes</p>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $this->totalBilledMonth }}</p>
        <p class="mt-1 text-sm text-slate-400">{{ now()->translatedFormat('F') }} {{ now()->year }}</p>
    </div>

    <div class="rounded-lg border border-slate-200 border-l-4 border-l-slate-700 bg-white p-5 shadow-sm">
        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Cobrado este año</p>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $this->totalBilledYear }}</p>
        <p class="mt-1 text-sm text-slate-400">Acumulado {{ now()->year }}</p>
    </div>

    <div class="rounded-lg border border-slate-200 border-l-4 border-l-emerald-500 bg-white p-5 shadow-sm">
        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Completados este mes</p>
        <p class="mt-2 text-3xl font-bold text-slate-900">{{ $this->completedThisMonth }}</p>
        <p class="mt-1 text-sm text-slate-400">Jobs finalizados</p>
    </div>

    <div class="rounded-lg border border-slate-200 border-l-4 border-l-rose-400 bg-white p-5 shadow-sm">
        <p class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Pendientes / En progreso</p>
        <p class="mt-2 text-3xl font-bold text-rose-500">{{ $this->pendingCount }}</p>
        <p class="mt-1 text-sm text-slate-400">Jobs activos</p>
    </div>

</div>
