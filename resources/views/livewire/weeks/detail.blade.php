<div class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a
            href="{{ route('payroll.weeks') }}"
            wire:navigate
            class="rounded-md p-1.5 text-slate-400 transition-colors hover:bg-slate-100 hover:text-slate-700"
            title="Volver a semanas"
        >
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <flux:heading size="xl">Semana del {{ $this->week->formattedRange() }}</flux:heading>
            @if ($this->week->notes)
                <p class="mt-0.5 text-sm text-slate-500">{{ $this->week->notes }}</p>
            @endif
        </div>
        <button
            type="button"
            wire:click="deleteWeek"
            wire:confirm="¿Eliminar esta semana? Se borrarán todos los pagos y asignaciones."
            class="inline-flex items-center gap-1.5 rounded-md border border-rose-200 bg-white px-3 py-1.5 text-sm font-semibold text-rose-600 transition-colors hover:bg-rose-50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Eliminar semana
        </button>
    </div>

    {{-- Divider: Asignaciones del día --}}
    <div class="flex items-center py-1">
        <div class="grow border-t border-slate-200"></div>
        <span class="mx-4 shrink-0 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Asignaciones del día</span>
        <div class="grow border-t border-slate-200"></div>
    </div>

    {{-- Cards de asignaciones diarias --}}
    @if (count($entries) > 0)
        @php
            $dayAbbr = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        @endphp
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @foreach ($entries as $entryId => $entry)
                <div wire:key="card-{{ $entryId }}" class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-50 px-4 py-2.5">
                        <p class="font-semibold text-slate-800">{{ $entry['worker_name'] }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 p-4 lg:grid-cols-4">
                        @foreach ($this->weekDays as $day)
                            @php $dateKey = $day->format('Y_m_d'); @endphp
                            <div wire:key="day-{{ $entryId }}-{{ $dateKey }}">
                                <span class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                    {{ $dayAbbr[$day->dayOfWeek] }} {{ $day->day }}
                                </span>
                                <textarea
                                    wire:model.live.debounce.500ms="assignments.{{ $entryId }}.{{ $dateKey }}"
                                    placeholder="Sin asignación"
                                    rows="2"
                                    x-data="{ resize() { $el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'; } }"
                                    x-init="resize()"
                                    @input="resize()"
                                    class="w-full resize-none rounded-md border py-1.5 px-2 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-300 focus:outline-none focus:ring-1 focus:ring-amber-200 {{ !empty($assignments[$entryId][$dateKey] ?? '') ? 'border-amber-300 bg-white' : 'border-slate-200 bg-slate-50' }}"
                                ></textarea>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Divider: Pagos de la semana --}}
    <div class="flex items-center py-1">
        <div class="grow border-t border-slate-200"></div>
        <span class="mx-4 shrink-0 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Pagos de la semana</span>
        <div class="grow border-t border-slate-200"></div>
    </div>

    {{-- Tabla de pagos --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead>
                    <tr class="bg-slate-900">
                        <th class="py-3.5 pl-5 pr-3 text-left text-xs font-semibold uppercase tracking-wider text-white">Trabajador</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Pago base</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Extra $</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Categorías / Nota</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Total</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Estado</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Nota de pago</th>
                        <th class="px-3 py-3.5 text-left text-xs font-semibold uppercase tracking-wider text-white">Fecha de pago</th>
                        <th class="px-3 py-3.5"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @php
                        $grandTotal = 0;
                        $totalBasePay = 0;
                        $totalExtraAmount = 0;
                        $paidCount = 0;
                        $totalCount = count($entries);
                        $categoryOptions = ['Materiales', 'Gasolina', 'Comida', 'Herramientas', 'Transporte', 'Otro'];
                    @endphp

                    @forelse ($entries as $entryId => $entry)
                        @php
                            $basePay = $entry['base_pay'] !== '' ? (float)$entry['base_pay'] : null;
                            $extraAmount = $entry['extra_amount'] !== '' ? (float)$entry['extra_amount'] : null;
                            $rowTotal = ($basePay !== null || $extraAmount !== null)
                                ? ($basePay ?? 0) + ($extraAmount ?? 0)
                                : null;

                            if ($basePay !== null) $totalBasePay += $basePay;
                            if ($extraAmount !== null) $totalExtraAmount += $extraAmount;
                            if ($rowTotal !== null) $grandTotal += $rowTotal;
                            if ($entry['payment_status'] === 'paid') $paidCount++;

                            $selectedCategories = $entry['extra_categories'] ?? [];

                            $indicator = match($entry['payment_status']) {
                                'paid'    => 'bg-emerald-400',
                                'partial' => 'bg-amber-400',
                                'refund'  => 'bg-rose-400',
                                default   => 'bg-transparent',
                            };
                            $rowBg = match($entry['payment_status']) {
                                'paid'    => 'bg-emerald-50',
                                'partial' => 'bg-amber-50',
                                'refund'  => 'bg-rose-50',
                                default   => 'bg-white',
                            };
                        @endphp

                        <tr wire:key="entry-{{ $entryId }}" class="{{ $rowBg }} transition-colors">

                            <td class="relative whitespace-nowrap py-3 pl-5 pr-3">
                                <span class="absolute inset-y-0 left-0 w-0.75 {{ $indicator }}"></span>
                                <span class="font-semibold text-slate-800">{{ $entry['worker_name'] }}</span>
                            </td>

                            <td class="whitespace-nowrap px-3 py-3">
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-slate-400">$</span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        wire:model.live.debounce.500ms="entries.{{ $entryId }}.base_pay"
                                        placeholder="0.00"
                                        class="w-24 rounded-md border border-slate-200 bg-white py-1.5 pl-5 pr-2 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                    />
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-3 py-3">
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-slate-400">$</span>
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        wire:model.live.debounce.500ms="entries.{{ $entryId }}.extra_amount"
                                        placeholder="0.00"
                                        class="w-24 rounded-md border border-slate-200 bg-white py-1.5 pl-5 pr-2 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                    />
                                </div>
                            </td>

                            <td class="px-3 py-3" style="min-width: 220px;">
                                <div class="mb-2 flex flex-wrap gap-1">
                                    @foreach ($categoryOptions as $cat)
                                        <button
                                            type="button"
                                            wire:click="toggleCategory({{ $entryId }}, '{{ $cat }}')"
                                            class="rounded-full border px-2 py-0.5 text-xs transition-colors
                                                {{ in_array($cat, $selectedCategories)
                                                    ? 'border-amber-400 bg-amber-500 font-semibold text-slate-900'
                                                    : 'border-slate-200 bg-slate-100 font-medium text-slate-600' }}"
                                        >{{ $cat }}</button>
                                    @endforeach
                                </div>
                                <input
                                    type="text"
                                    wire:model.live.debounce.500ms="entries.{{ $entryId }}.extra_free_note"
                                    placeholder="Nota adicional..."
                                    class="w-full rounded-md border border-slate-200 bg-white py-1 px-2 text-xs text-slate-700 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                />
                            </td>

                            <td class="whitespace-nowrap px-3 py-3 font-semibold">
                                @if ($rowTotal !== null)
                                    <span class="text-slate-900">${{ number_format($rowTotal, 2) }}</span>
                                @else
                                    <span class="text-slate-300">–</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-3 py-3">
                                <select
                                    wire:model.live="entries.{{ $entryId }}.payment_status"
                                    class="rounded-md border py-1.5 px-2 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-amber-200
                                        {{ match($entry['payment_status']) {
                                            'paid'    => 'border-emerald-200 bg-emerald-100 text-emerald-700 focus:border-emerald-400',
                                            'partial' => 'border-amber-200 bg-amber-100 text-amber-700 focus:border-amber-400',
                                            'refund'  => 'border-rose-200 bg-rose-100 text-rose-700 focus:border-rose-400',
                                            default   => 'border-slate-200 bg-slate-100 text-slate-600 focus:border-slate-400',
                                        } }}"
                                >
                                    <option value="pending">Pendiente</option>
                                    <option value="paid">Pagado</option>
                                    <option value="partial">Parcial</option>
                                    <option value="refund">Devolución</option>
                                </select>
                            </td>

                            <td class="px-3 py-3" style="min-width: 140px;">
                                <input
                                    type="text"
                                    wire:model.live.debounce.500ms="entries.{{ $entryId }}.payment_notes"
                                    placeholder="ej. recibos pendientes…"
                                    class="w-full rounded-md border border-slate-200 bg-white py-1.5 px-2.5 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                />
                            </td>

                            <td class="whitespace-nowrap px-3 py-3">
                                @if ($entry['payment_status'] !== 'pending')
                                    <input
                                        type="date"
                                        wire:model.live="entries.{{ $entryId }}.paid_at"
                                        class="rounded-md border border-slate-200 bg-white py-1.5 px-2.5 text-sm text-slate-800 focus:border-amber-500 focus:outline-none focus:ring-1 focus:ring-amber-200"
                                    />
                                @else
                                    <span class="text-slate-300">–</span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-3 py-3">
                                <button
                                    type="button"
                                    wire:click="removeWorker({{ $entryId }})"
                                    wire:confirm="¿Quitar a {{ $entry['worker_name'] }} de esta semana?"
                                    class="rounded-md border border-slate-200 bg-white p-1.5 text-slate-400 transition-colors hover:border-rose-200 hover:bg-rose-50 hover:text-rose-500"
                                    title="Quitar trabajador"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-16 text-center text-slate-400">
                                No hay trabajadores en esta semana.
                            </td>
                        </tr>
                    @endforelse

                    {{-- Fila de totales --}}
                    @if ($totalCount > 0)
                        <tr class="border-t-2 border-slate-200 bg-slate-50">
                            <td class="whitespace-nowrap py-3 pl-5 pr-3 text-xs text-slate-400">
                                {{ $paidCount }} de {{ $totalCount }} pagado{{ $paidCount !== 1 ? 's' : '' }}
                            </td>
                            <td class="px-3 py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Sueldos</p>
                                <p class="text-sm font-semibold text-slate-700">${{ number_format($totalBasePay, 2) }}</p>
                            </td>
                            <td class="px-3 py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Gastos</p>
                                <p class="text-sm font-semibold text-amber-600">${{ number_format($totalExtraAmount, 2) }}</p>
                            </td>
                            <td class="px-3 py-3"></td>
                            <td class="whitespace-nowrap px-3 py-3">
                                <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Gran Total</p>
                                <p class="text-base font-bold text-slate-900">${{ number_format($grandTotal, 2) }}</p>
                            </td>
                            <td colspan="4" class="px-3 py-3"></td>
                        </tr>
                    @endif

                </tbody>

            </table>
        </div>
    </div>

</div>
