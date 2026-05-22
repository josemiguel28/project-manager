<div class="min-h-screen bg-slate-50">

    {{-- ═══════════════════════════════════════════
         NAVBAR
    ════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-30 bg-slate-900">
        <div class="mx-auto flex max-w-screen-2xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                {{-- Wrench icon --}}
                <svg class="h-5 w-5 shrink-0 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75a4.5 4.5 0 01-4.884 4.484c-1.076-.091-2.264.071-2.95.904l-7.152 8.684a2.548 2.548 0 11-3.586-3.586l8.684-7.152c.833-.686.995-1.874.904-2.95a4.5 4.5 0 016.336-4.486l-3.276 3.276a3.004 3.004 0 002.25 2.25l3.276-3.276c.256.565.398 1.192.398 1.852z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.867 19.125h.008v.008h-.008v-.008z"/>
                </svg>
                <div>
                    <p class="text-sm font-bold leading-tight text-white">Parker's Job Store</p>
                    <p class="mt-0.5 text-[11px] leading-none text-slate-400">Work Tracker</p>
                </div>
            </div>
            <button
                wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 rounded-md bg-amber-500 px-4 py-2 text-sm font-bold text-slate-900 transition-colors hover:bg-amber-600 active:scale-95"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Agregar Job
            </button>
        </div>
    </header>

    <main class="mx-auto max-w-screen-2xl space-y-5 px-6 py-6">

        {{-- ═══════════════════════════════════════════
             DASHBOARD CARDS
        ════════════════════════════════════════════ --}}
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

        {{-- ═══════════════════════════════════════════
             BÚSQUEDA + FILTROS
        ════════════════════════════════════════════ --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por tienda, sitio, Work Order ID o asignado…"
                    class="w-full rounded-md border border-slate-300 bg-white py-2 pl-9 pr-4 text-sm text-slate-800 placeholder-slate-400 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"
                />
            </div>
            <div class="flex gap-1.5">
                @foreach ([
                    'all'        => 'Todos',
                    'pending'    => 'Pendientes',
                    'in_progress'=> 'En Progreso',
                    'completed'  => 'Completados',
                ] as $value => $label)
                    <button
                        wire:click="$set('statusFilter', '{{ $value }}')"
                        class="rounded-md px-3 py-1.5 text-xs font-semibold transition-colors
                            {{ $statusFilter === $value
                                ? 'bg-slate-900 text-white'
                                : 'border border-slate-200 bg-white text-slate-600 hover:border-slate-400 hover:text-slate-900' }}"
                    >{{ $label }}</button>
                @endforeach
            </div>
        </div>

        {{-- ═══════════════════════════════════════════
             TABLA
        ════════════════════════════════════════════ --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    {{-- HEADER --}}
                    <thead>
                        <tr class="bg-slate-900">
                            <th class="py-3.5 pl-5 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Store #</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Sitio</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Descripción / Nota</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Work Order ID</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Inicio</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Fin</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Asignado</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Monto</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Invoice #</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Estado</th>
                            <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white"></th>
                        </tr>
                    </thead>

                    {{-- BODY --}}
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($this->workOrders as $wo)
                            @php
                                $isEven = $loop->index % 2 !== 0;
                                $indicator = match($wo->status) {
                                    'completed'  => 'bg-emerald-400',
                                    'in_progress'=> 'bg-amber-400',
                                    default      => 'bg-transparent',
                                };
                                $rowBg = match($wo->status) {
                                    'completed'  => 'bg-emerald-50 hover:bg-emerald-100/60',
                                    'in_progress'=> 'bg-amber-50 hover:bg-amber-100/60',
                                    default      => ($isEven ? 'bg-slate-50' : 'bg-white') . ' hover:bg-amber-50',
                                };
                            @endphp
                            <tr wire:key="row-{{ $wo->id }}" class="group {{ $rowBg }} transition-colors">

                                {{-- Store # + left indicator --}}
                                <td class="relative whitespace-nowrap py-4 pl-5 pr-3">
                                    <span class="absolute inset-y-0 left-0 w-0.75 {{ $indicator }}"></span>
                                    <span class="font-semibold text-slate-800">{{ $wo->store_number }}</span>
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 font-medium text-slate-700">{{ $wo->site }}</td>

                                {{-- Descripción + nota inline --}}
                                <td class="px-3 py-4" style="max-width: 220px; min-width: 150px;">
                                    <p class="truncate font-medium text-slate-800" title="{{ $wo->service_description }}">{{ $wo->service_description }}</p>
                                    @if ($wo->notes)
                                        <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-slate-400" title="{{ $wo->notes }}">
                                            <svg class="-mt-px mr-0.5 inline h-3 w-3 shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                            </svg>{{ $wo->notes }}
                                        </p>
                                    @endif
                                </td>

                                {{-- Work Order ID --}}
                                <td class="whitespace-nowrap px-3 py-4">
                                    @if ($wo->gmail_link)
                                        <a href="{{ $wo->gmail_link }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 font-mono text-xs font-semibold text-amber-600 underline-offset-2 hover:text-amber-800 hover:underline">
                                            {{ $wo->work_order_id }}
                                            <svg class="h-3 w-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="font-mono text-xs text-slate-600">{{ $wo->work_order_id }}</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 text-xs text-slate-500">
                                    {{ $wo->date_started?->format('m/d/Y') ?? '' }}<span class="text-slate-300">{{ $wo->date_started ? '' : '–' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-xs text-slate-500">
                                    {{ $wo->day_done?->format('m/d/Y') ?? '' }}<span class="text-slate-300">{{ $wo->day_done ? '' : '–' }}</span>
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 text-slate-700">
                                    @if ($wo->assigned_name)
                                        {{ $wo->assigned_name }}
                                    @else
                                        <span class="text-slate-300">–</span>
                                    @endif
                                </td>

                                <td class="whitespace-nowrap px-3 py-4 font-semibold text-slate-800">
                                    @if ($wo->amount !== null)
                                        ${{ number_format($wo->amount, 2) }}
                                    @else
                                        <span class="text-slate-300">–</span>
                                    @endif
                                </td>

                                {{-- Invoice # --}}
                                <td class="whitespace-nowrap px-3 py-4">
                                    @if ($wo->invoice_number && $wo->invoice_link)
                                        <a href="{{ $wo->invoice_link }}" target="_blank" rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 text-xs font-semibold text-amber-600 underline-offset-2 hover:text-amber-800 hover:underline">
                                            {{ $wo->invoice_number }}
                                            <svg class="h-3 w-3 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @elseif ($wo->invoice_number)
                                        <span class="text-xs text-slate-600">{{ $wo->invoice_number }}</span>
                                    @else
                                        <span class="text-slate-300">–</span>
                                    @endif
                                </td>

                                {{-- Estado + quick-change --}}
                                <td class="whitespace-nowrap px-3 py-4">
                                    <div class="flex flex-col items-start gap-1.5" wire:key="status-cell-{{ $wo->id }}">

                                        <button
                                            wire:click="toggleStatusMenu({{ $wo->id }})"
                                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold transition-all active:scale-95
                                                {{ $wo->status === 'completed'   ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : '' }}
                                                {{ $wo->status === 'in_progress' ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : '' }}
                                                {{ $wo->status === 'pending'     ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : '' }}"
                                        >
                                            @if ($wo->status === 'completed')
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Completado
                                            @elseif ($wo->status === 'in_progress')
                                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-amber-500"></span> En Progreso
                                            @else
                                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span> Pendiente
                                            @endif
                                            <svg class="h-3 w-3 transition-transform {{ $openStatusMenu === $wo->id ? 'rotate-180' : '' }}"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>

                                        @if ($openStatusMenu === $wo->id)
                                            <div class="flex flex-col gap-1" wire:key="status-opts-{{ $wo->id }}">
                                                @foreach ([
                                                    'pending'     => ['Pendiente',  'bg-slate-100 text-slate-600 hover:bg-slate-200'],
                                                    'in_progress' => ['En Progreso', 'bg-amber-100 text-amber-700 hover:bg-amber-200'],
                                                    'completed'   => ['Completado',  'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'],
                                                ] as $val => [$lbl, $cls])
                                                    @if ($val !== $wo->status)
                                                        <button
                                                            wire:click="quickChangeStatus({{ $wo->id }}, '{{ $val }}')"
                                                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium transition-colors {{ $cls }}"
                                                        >{{ $lbl }}</button>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Acciones --}}
                                <td class="whitespace-nowrap px-3 py-4">
                                    <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                        <button
                                            wire:click="openEditModal({{ $wo->id }})"
                                            title="Editar"
                                            class="rounded p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-700 transition-colors"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button
                                            wire:click="delete({{ $wo->id }})"
                                            wire:confirm="¿Eliminar este job? Esta acción no se puede deshacer."
                                            title="Eliminar"
                                            class="rounded p-1.5 text-slate-400 hover:bg-red-50 hover:text-red-600 transition-colors"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-20 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                    </svg>
                                    <p class="mt-4 font-semibold text-slate-500">No se encontraron trabajos</p>
                                    <p class="mt-1 text-sm text-slate-400">
                                        {{ $search ? 'Intenta con otros términos de búsqueda.' : 'Agrega un nuevo job con el botón superior.' }}
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </main>

    {{-- ═══════════════════════════════════════════
         MODAL
    ════════════════════════════════════════════ --}}
    @if ($showModal)
        <div
            class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-slate-900/60 px-4 py-8 backdrop-blur-sm"
            wire:click.self="closeModal"
        >
            <div class="w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">

                {{-- Modal header (dark) --}}
                <div class="flex items-center justify-between bg-slate-900 px-6 py-4">
                    <h2 class="text-base font-bold text-white">
                        {{ $editingId ? 'Editar Job' : 'Nuevo Job' }}
                    </h2>
                    <button wire:click="closeModal"
                        class="rounded-md p-1.5 text-slate-400 transition-colors hover:bg-slate-700 hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-5 px-6 py-5">

                    {{-- Información del trabajo --}}
                    <fieldset class="space-y-3">
                        <legend class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Información del trabajo</legend>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Store Number <span class="text-red-400">*</span></label>
                                <input type="text" wire:model="store_number" placeholder="033"
                                    class="w-full rounded-md border py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('store_number') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"/>
                                @error('store_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Sitio <span class="text-red-400">*</span></label>
                                <input type="text" wire:model="site" placeholder="BLUFFTON"
                                    class="w-full rounded-md border py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('site') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"/>
                                @error('site') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2">
                                <label class="mb-1 block text-xs font-medium text-slate-700">Descripción del servicio <span class="text-red-400">*</span></label>
                                <textarea wire:model="service_description" rows="2" placeholder="Descripción breve del trabajo…"
                                    class="w-full rounded-md border py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('service_description') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"></textarea>
                                @error('service_description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Work Order ID <span class="text-red-400">*</span></label>
                                <input type="text" wire:model="work_order_id" placeholder="WOT0141360"
                                    class="w-full rounded-md border py-2 px-3 font-mono text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('work_order_id') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"/>
                                @error('work_order_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Link Gmail</label>
                                <input type="url" wire:model="gmail_link" placeholder="https://mail.google.com/…"
                                    class="w-full rounded-md border py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('gmail_link') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"/>
                                @error('gmail_link') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Asignación y fechas --}}
                    <fieldset class="space-y-3">
                        <legend class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Asignación y fechas</legend>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Asignado a</label>
                                <input type="text" wire:model="assigned_name" placeholder="Nombre del técnico"
                                    class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"/>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Estado <span class="text-red-400">*</span></label>
                                <select wire:model="status"
                                    class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200">
                                    <option value="pending">Pendiente</option>
                                    <option value="in_progress">En Progreso</option>
                                    <option value="completed">Completado</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Fecha de inicio</label>
                                <input type="date" wire:model="date_started"
                                    class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"/>
                                @error('date_started') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Fecha de finalización</label>
                                <input type="date" wire:model="day_done"
                                    class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"/>
                                @error('day_done') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Facturación --}}
                    <fieldset class="space-y-3">
                        <legend class="text-[10px] font-semibold uppercase tracking-widest text-slate-500">Facturación</legend>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Monto</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 font-medium text-slate-400">$</span>
                                    <input type="number" step="0.01" min="0" wire:model="amount" placeholder="0.00"
                                        class="w-full rounded-md border border-slate-300 py-2 pl-7 pr-3 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"/>
                                </div>
                                @error('amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">Número de Invoice</label>
                                <input type="text" wire:model="invoice_number" placeholder="INV-1042"
                                    class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"/>
                            </div>
                            <div class="col-span-2">
                                <label class="mb-1 block text-xs font-medium text-slate-700">Link Invoice QuickBooks</label>
                                <input type="url" wire:model="invoice_link" placeholder="https://app.qbo.intuit.com/…"
                                    class="w-full rounded-md border py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('invoice_link') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"/>
                                @error('invoice_link') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </fieldset>

                    <hr class="border-slate-100">

                    {{-- Notas del cliente --}}
                    <fieldset>
                        <legend class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Notas del cliente</legend>
                        <textarea wire:model="notes" rows="3" placeholder="Comentarios u observaciones adicionales…"
                            class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"></textarea>
                    </fieldset>

                </div>

                {{-- Modal footer --}}
                <div class="flex justify-end gap-2 border-t border-slate-100 px-6 py-4">
                    <button wire:click="closeModal"
                        class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        Cancelar
                    </button>
                    <button wire:click="save"
                        class="inline-flex min-w-27.5 items-center justify-center gap-2 rounded-md bg-amber-500 px-4 py-2 text-sm font-bold text-slate-900 transition-colors hover:bg-amber-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1">
                        <span wire:loading.remove wire:target="save">Guardar Job</span>
                        <span wire:loading wire:target="save" class="inline-flex items-center gap-1.5">
                            <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Guardando…
                        </span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
