<div>
    @php
        $monthNames = [
            1 => 'Enero',   2 => 'Febrero', 3 => 'Marzo',    4 => 'Abril',
            5 => 'Mayo',    6 => 'Junio',   7 => 'Julio',    8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];
        $currentMonthKey = now()->format('Y-m');

        // Group weeks by year-month key (service already orders DESC, so groups appear newest-first)
        $groups = [];
        foreach ($this->workWeeks as $week) {
            $key = $week->start_date->format('Y-m');
            if (! isset($groups[$key])) {
                $groups[$key] = [
                    'label' => $monthNames[$week->start_date->month] . ' ' . $week->start_date->year,
                    'weeks' => [],
                ];
            }
            $groups[$key]['weeks'][] = $week;
        }

        $defaultOpen = array_key_exists($currentMonthKey, $groups) ? $currentMonthKey : array_key_first($groups);
    @endphp

    {{-- Empty state --}}
    @if (empty($groups))
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <svg class="h-14 w-14 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="mt-4 font-semibold text-slate-500">No hay semanas registradas</p>
            <p class="mt-1 text-sm text-slate-400">Crea una nueva semana con el botón superior</p>
        </div>

    {{-- Month accordion groups --}}
    @else
        <div x-data="{ open: @js($defaultOpen) }" class="space-y-3">

            @foreach ($groups as $monthKey => $group)
                @php
                    $monthTotal    = 0;
                    $pendingCount  = 0;
                    $weekCount     = count($group['weeks']);
                    foreach ($group['weeks'] as $w) {
                        $monthTotal   += $w->entries->sum(fn ($e) => (float) ($e->base_pay ?? 0) + (float) ($e->extra_amount ?? 0));
                        $pendingCount += $w->entries->where('payment_status', 'pending')->count();
                    }
                @endphp

                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

                    {{-- Month header --}}
                    <button
                        type="button"
                        x-on:click="open = (open === @js($monthKey)) ? null : @js($monthKey)"
                        class="flex w-full items-center gap-4 px-5 py-4 text-left transition-colors hover:bg-slate-50"
                    >
                        {{-- Chevron --}}
                        <svg
                            :class="open === @js($monthKey) ? 'rotate-180' : ''"
                            class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>

                        {{-- Month info --}}
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-lg font-bold text-slate-900">{{ $group['label'] }}</span>
                                @if ($pendingCount > 0)
                                    <span class="rounded-full bg-amber-500 px-2.5 py-0.5 text-xs font-semibold text-white">
                                        {{ $pendingCount }} pendiente{{ $pendingCount !== 1 ? 's' : '' }}
                                    </span>
                                @endif
                            </div>
                            <div class="mt-0.5 flex items-center gap-3">
                                <span class="text-sm text-slate-400">{{ $weekCount }} semana{{ $weekCount !== 1 ? 's' : '' }}</span>
                                @if ($monthTotal > 0)
                                    <span class="text-sm font-semibold text-slate-700">${{ number_format($monthTotal, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </button>

                    {{-- Expanded week rows --}}
                    <div
                        x-show="open === @js($monthKey)"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="divide-y divide-slate-100 border-t border-slate-100"
                    >
                        @foreach ($group['weeks'] as $week)
                            @php
                                $weekTotal   = $week->entries->sum(fn ($e) => (float) ($e->base_pay ?? 0) + (float) ($e->extra_amount ?? 0));
                                $paidCount   = $week->entries->where('payment_status', 'paid')->count();
                                $totalWorkers = $week->entries->count();

                                $start = $week->start_date;
                                $end   = $week->end_date;
                                $rangeLabel = ($start->month === $end->month)
                                    ? $start->day . ' - ' . $end->day . ' de ' . $monthNames[$start->month]
                                    : $start->day . ' de ' . $monthNames[$start->month] . ' - ' . $end->day . ' de ' . $monthNames[$end->month];
                            @endphp

                            <div wire:key="week-{{ $week->id }}" class="relative flex items-center gap-4 py-4 pl-6 pr-5 transition-colors hover:bg-amber-50">
                                <span class="absolute inset-y-0 left-0 w-0.75 bg-amber-400"></span>

                                {{-- Date range + meta --}}
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-slate-700">{{ $rangeLabel }}</p>
                                    <p class="mt-0.5 text-sm text-slate-400">
                                        {{ $totalWorkers }} trabajador{{ $totalWorkers !== 1 ? 'es' : '' }}
                                        @if ($totalWorkers > 0)
                                            &middot; {{ $paidCount }} pagado{{ $paidCount !== 1 ? 's' : '' }}
                                        @endif
                                        @if ($week->notes)
                                            &middot; <em>{{ $week->notes }}</em>
                                        @else
                                            &middot; <span class="not-italic text-slate-300">–</span>
                                        @endif
                                    </p>
                                </div>

                                {{-- Week total --}}
                                <div class="shrink-0">
                                    @if ($weekTotal > 0)
                                        <span class="font-semibold text-slate-800">${{ number_format($weekTotal, 2) }}</span>
                                    @else
                                        <span class="text-slate-300">–</span>
                                    @endif
                                </div>

                                {{-- Ver detalle --}}
                                <div class="shrink-0">
                                    <a
                                        href="{{ route('payroll.weeks.show', $week) }}"
                                        wire:navigate
                                        class="inline-flex items-center gap-1 rounded-md border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600 transition-colors hover:border-amber-400 hover:bg-amber-50 hover:text-amber-700"
                                    >
                                        Ver detalle
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach

        </div>
    @endif
</div>
