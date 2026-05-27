<?php

namespace App\Modules\Payroll\Livewire;

use App\Modules\Payroll\Services\PayrollService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class WeekList extends Component
{
    #[Computed]
    public function workWeeks()
    {
        return app(PayrollService::class)->getWeeks();
    }

    #[On('week-saved')]
    public function refresh(): void
    {
        unset($this->workWeeks);
    }

    public function deleteWeek(int $id): void
    {
        app(PayrollService::class)->deleteWeek($id);
        unset($this->workWeeks);
    }

    public function render()
    {
        return view('livewire.weeks.list');
    }
}
