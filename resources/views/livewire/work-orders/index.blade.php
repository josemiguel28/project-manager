<div class="space-y-5">

    <div class="flex items-center justify-between">
        <flux:heading size="xl">Work Orders</flux:heading>

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

    <livewire:work-orders.stats />
    <livewire:work-orders.table />
    <livewire:work-orders.form />

</div>
