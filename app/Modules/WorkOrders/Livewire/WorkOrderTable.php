<?php

namespace App\Modules\WorkOrders\Livewire;

use App\Modules\WorkOrders\Services\WorkOrderService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkOrderTable extends Component
{
    public string $search = '';
    public string $statusFilter = 'pending';
    public ?int $openStatusMenu = null;

    #[Computed]
    public function workOrders()
    {
        return app(WorkOrderService::class)->getFiltered($this->search, $this->statusFilter);
    }

    public function toggleStatusMenu(int $id): void
    {
        $this->openStatusMenu = $this->openStatusMenu === $id ? null : $id;
    }

    public function quickChangeStatus(int $id, string $status): void
    {
        app(WorkOrderService::class)->quickChangeStatus($id, $status);
        $this->openStatusMenu = null;
        unset($this->workOrders);
        $this->dispatch('work-order-status-changed');
    }

    public function openEditModal(int $id): void
    {
        $this->openStatusMenu = null;
        $this->dispatch('open-edit-modal', id: $id);
    }

    public function delete(int $id): void
    {
        app(WorkOrderService::class)->delete($id);
        $this->openStatusMenu = null;
        unset($this->workOrders);
        $this->dispatch('work-order-deleted');
    }

    #[On('work-order-saved')]
    public function refreshTable(): void
    {
        unset($this->workOrders);
    }

    public function render()
    {
        return view('livewire.work-orders.table');
    }
}
