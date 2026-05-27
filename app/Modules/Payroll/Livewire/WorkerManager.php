<?php

namespace App\Modules\Payroll\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WorkerManager extends Component
{
    public function openCreateModal(): void
    {
        $this->dispatch('open-worker-create-modal');
    }

    public function render()
    {
        return view('livewire.workers.index');
    }
}
