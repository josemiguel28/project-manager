<?php

namespace App\Modules\WorkOrders\Livewire;

use App\Modules\WorkOrders\Services\WorkOrderService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkOrderStats extends Component
{
    #[Computed]
    public function totalBilledMonth(): string
    {
        return app(WorkOrderService::class)->totalBilledMonth();
    }

    #[Computed]
    public function totalBilledYear(): string
    {
        return app(WorkOrderService::class)->totalBilledYear();
    }

    #[Computed]
    public function completedThisMonth(): int
    {
        return app(WorkOrderService::class)->completedThisMonth();
    }

    #[Computed]
    public function pendingCount(): int
    {
        return app(WorkOrderService::class)->pendingCount();
    }

    #[On('work-order-saved')]
    #[On('work-order-deleted')]
    #[On('work-order-status-changed')]
    public function refresh(): void
    {
        unset($this->totalBilledMonth, $this->totalBilledYear, $this->completedThisMonth, $this->pendingCount);
    }

    public function render()
    {
        return view('livewire.work-orders.stats');
    }
}
