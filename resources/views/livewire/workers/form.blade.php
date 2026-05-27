<div>
    @if ($showModal)
        <div
            class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-slate-900/60 px-4 py-8 backdrop-blur-sm"
            wire:click.self="closeModal"
        >
            <div class="w-full max-w-md overflow-hidden rounded-xl bg-white shadow-2xl">

                <div class="flex items-center justify-between bg-slate-900 px-6 py-4">
                    <h2 class="text-base font-bold text-white">
                        {{ $editingId ? 'Editar Trabajador' : 'Nuevo Trabajador' }}
                    </h2>
                    <button wire:click="closeModal"
                        class="rounded-md p-1.5 text-slate-400 transition-colors hover:bg-slate-700 hover:text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="space-y-4 px-6 py-5">

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-700">
                            Nombre <span class="text-red-400">*</span>
                        </label>
                        <input
                            type="text"
                            wire:model="name"
                            placeholder="Nombre completo"
                            class="w-full rounded-md border py-2 px-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('name') ? 'border-red-300 bg-red-50' : 'border-slate-300' }}"
                        />
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-700">Sueldo base semanal</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 font-medium text-slate-400">$</span>
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                wire:model="base_weekly_pay"
                                placeholder="Dejar vacío si no tiene sueldo fijo"
                                class="w-full rounded-md border border-slate-300 py-2 pl-7 pr-3 text-sm text-slate-800 placeholder-slate-300 focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-200 {{ $errors->has('base_weekly_pay') ? 'border-red-300 bg-red-50' : '' }}"
                            />
                        </div>
                        @error('base_weekly_pay')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-400">Dejar vacío si no tiene sueldo fijo.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            type="checkbox"
                            id="is_active"
                            wire:model="is_active"
                            class="h-4 w-4 rounded border-slate-300 text-amber-500 focus:ring-amber-400"
                        />
                        <label for="is_active" class="text-sm font-medium text-slate-700">Trabajador activo</label>
                    </div>

                </div>

                <div class="flex justify-end gap-2 border-t border-slate-100 px-6 py-4">
                    <button wire:click="closeModal"
                        class="rounded-md border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        Cancelar
                    </button>
                    <button wire:click="save"
                        class="inline-flex min-w-28 items-center justify-center gap-2 rounded-md bg-amber-500 px-4 py-2 text-sm font-bold text-slate-900 transition-colors hover:bg-amber-600 active:scale-95 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1">
                        <span wire:loading.remove wire:target="save">Guardar</span>
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
