<div class="space-y-5">

    {{-- Búsqueda + Filtros --}}
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
                'all'         => 'Todos',
                'pending'     => 'Pendientes',
                'in_progress' => 'En Progreso',
                'completed'   => 'Completados',
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

    {{-- Tabla --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

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

                <tbody class="divide-y divide-slate-100">
                    @forelse ($this->workOrders as $wo)
                        @php
                            $isEven = $loop->index % 2 !== 0;
                            $indicator = match($wo->status) {
                                'completed'   => 'bg-emerald-400',
                                'in_progress' => 'bg-amber-400',
                                default       => 'bg-transparent',
                            };
                            $rowBg = match($wo->status) {
                                'completed'   => 'bg-emerald-50 hover:bg-emerald-100/60',
                                'in_progress' => 'bg-amber-50 hover:bg-amber-100/60',
                                default       => ($isEven ? 'bg-slate-50' : 'bg-white') . ' hover:bg-amber-50',
                            };
                        @endphp
                        <tr wire:key="row-{{ $wo->id }}" class="group {{ $rowBg }} transition-colors">

                            <td class="relative whitespace-nowrap py-4 pl-5 pr-3">
                                <span class="absolute inset-y-0 left-0 w-0.75 {{ $indicator }}"></span>
                                <span class="font-semibold text-slate-800">{{ $wo->store_number }}</span>
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 font-medium text-slate-700">{{ $wo->site }}</td>

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

                            <td class="whitespace-nowrap px-3 py-4">
                                <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                    <button
                                        wire:click="openEditModal({{ $wo->id }})"
                                        title="Editar"
                                        class="rounded p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button
                                        wire:click="delete({{ $wo->id }})"
                                        wire:confirm="¿Eliminar este job? Esta acción no se puede deshacer."
                                        title="Eliminar"
                                        class="rounded p-1.5 text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600"
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

</div>
