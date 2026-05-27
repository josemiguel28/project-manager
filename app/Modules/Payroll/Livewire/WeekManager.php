<?php

namespace App\Modules\Payroll\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WeekManager extends Component
{
    public function openCreateModal(): void
    {
        $this->dispatch('open-week-create-modal');
    }

    public function render()
    {
        return view('livewire.weeks.index');
    }
}
