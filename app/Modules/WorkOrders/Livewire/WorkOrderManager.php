<?php

namespace App\Modules\WorkOrders\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WorkOrderManager extends Component
{
    public function openCreateModal(): void
    {
        $this->dispatch('open-create-modal');
    }

    public function render()
    {
        return view('livewire.work-orders.index');
    }
}
