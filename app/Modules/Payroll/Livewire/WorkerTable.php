<?php

namespace App\Modules\Payroll\Livewire;

use App\Modules\Payroll\Services\PayrollService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkerTable extends Component
{
    #[Computed]
    public function workers()
    {
        return app(PayrollService::class)->getWorkers();
    }

    public function openEditModal(int $id): void
    {
        $this->dispatch('open-worker-edit-modal', id: $id);
    }

    public function toggleActive(int $id): void
    {
        app(PayrollService::class)->toggleWorkerActive($id);
        unset($this->workers);
    }

    #[On('worker-saved')]
    public function refresh(): void
    {
        unset($this->workers);
    }

    public function render()
    {
        return view('livewire.workers.table');
    }
}
