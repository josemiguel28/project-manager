<div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead>
                    <tr class="bg-slate-900">
                        <th class="py-3.5 pl-5 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Nombre</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Sueldo base semanal</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Estado</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($this->workers as $worker)
                        @php
                            $isEven = $loop->index % 2 !== 0;
                            $rowBg = $worker->is_active
                                ? ($isEven ? 'bg-slate-50' : 'bg-white') . ' hover:bg-amber-50'
                                : 'bg-slate-100 opacity-60';
                        @endphp
                        <tr wire:key="worker-{{ $worker->id }}" class="group {{ $rowBg }} transition-colors">

                            <td class="relative whitespace-nowrap py-4 pl-5 pr-3">
                                <span class="absolute inset-y-0 left-0 w-0.75 {{ $worker->is_active ? 'bg-amber-400' : 'bg-slate-300' }}"></span>
                                <span class="font-semibold text-slate-800">{{ $worker->name }}</span>
                            </td>

                            <td class="whitespace-nowrap px-3 py-4 font-semibold text-slate-700">
                                @if ($worker->base_weekly_pay !== null)
                                    ${{ number_format($worker->base_weekly_pay, 2) }}
                                @else
                                    <span class="text-slate-400 font-normal">Sin sueldo fijo</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-3 py-4">
                                @if ($worker->is_active)
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-500">
                                        <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-3 py-4">
                                <div class="flex items-center gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                    <button
                                        wire:click="openEditModal({{ $worker->id }})"
                                        title="Editar"
                                        class="rounded p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    @if ($worker->is_active)
                                        <button
                                            wire:click="toggleActive({{ $worker->id }})"
                                            wire:confirm="¿Desactivar a {{ $worker->name }}?"
                                            title="Desactivar"
                                            class="rounded p-1.5 text-slate-400 transition-colors hover:bg-amber-50 hover:text-amber-600"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button
                                            wire:click="toggleActive({{ $worker->id }})"
                                            title="Activar"
                                            class="rounded p-1.5 text-slate-400 transition-colors hover:bg-emerald-50 hover:text-emerald-600"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-20 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="mt-4 font-semibold text-slate-500">No hay trabajadores registrados</p>
                                <p class="mt-1 text-sm text-slate-400">Agrega un trabajador con el botón superior.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</div>
