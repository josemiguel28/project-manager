<div>
    @if ($showModal)
        <div
            class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-slate-900/60 px-4 py-8 backdrop-blur-sm"
            wire:click.self="closeModal"
        >
            <div class="w-full max-w-2xl overflow-hidden rounded-xl bg-white shadow-2xl">

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

                    <fieldset>
                        <legend class="mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-500">Notas del cliente</legend>
                        <textarea wire:model="notes" rows="3" placeholder="Comentarios u observaciones adicionales…"
                            class="w-full rounded-md border border-slate-300 py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200"></textarea>
                    </fieldset>

                </div>

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
