<?php

namespace App\Livewire;

use App\Models\WorkOrder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.parkers')]

class WorkOrderManager extends Component
{
    public string $search = '';
    public string $statusFilter = 'pending';
    public ?int $openStatusMenu = null;

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $store_number = '';
    public string $site = '';
    public string $service_description = '';
    public string $work_order_id = '';
    public string $gmail_link = '';
    public string $date_started = '';
    public string $day_done = '';
    public string $assigned_name = '';
    public string $amount = '';
    public string $invoice_number = '';
    public string $invoice_link = '';
    public string $notes = '';
    public string $status = 'pending';

    #[Computed]
    public function workOrders()
    {
        return WorkOrder::query()
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('store_number', 'like', '%'.$this->search.'%')
                        ->orWhere('site', 'like', '%'.$this->search.'%')
                        ->orWhere('work_order_id', 'like', '%'.$this->search.'%')
                        ->orWhere('assigned_name', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->statusFilter !== 'all', fn ($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('created_at')
            ->get();
    }

    #[Computed]
    public function totalBilledMonth(): string
    {
        $total = WorkOrder::query()
            ->where('status', 'completed')
            ->whereMonth('day_done', now()->month)
            ->whereYear('day_done', now()->year)
            ->sum('amount');

        return '$'.number_format($total, 2);
    }

    #[Computed]
    public function totalBilledYear(): string
    {
        $total = WorkOrder::query()
            ->where('status', 'completed')
            ->whereYear('day_done', now()->year)
            ->sum('amount');

        return '$'.number_format($total, 2);
    }

    #[Computed]
    public function completedThisMonth(): int
    {
        return WorkOrder::query()
            ->where('status', 'completed')
            ->whereMonth('day_done', now()->month)
            ->whereYear('day_done', now()->year)
            ->count();
    }

    #[Computed]
    public function pendingCount(): int
    {
        return WorkOrder::query()
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
    }

    public function toggleStatusMenu(int $id): void
    {
        $this->openStatusMenu = $this->openStatusMenu === $id ? null : $id;
    }

    public function quickChangeStatus(int $id, string $status): void
    {
        $wo = WorkOrder::findOrFail($id);
        $data = ['status' => $status];
        if ($status === 'completed' && ! $wo->day_done) {
            $data['day_done'] = now()->format('Y-m-d');
        }
        $wo->update($data);
        $this->openStatusMenu = null;
        unset($this->workOrders, $this->totalBilledMonth, $this->totalBilledYear, $this->completedThisMonth, $this->pendingCount);
    }

    public function openCreateModal(): void
    {
        $this->openStatusMenu = null;
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->openStatusMenu = null;
        $wo = WorkOrder::findOrFail($id);
        $this->editingId = $id;
        $this->store_number = $wo->store_number;
        $this->site = $wo->site;
        $this->service_description = $wo->service_description;
        $this->work_order_id = $wo->work_order_id;
        $this->gmail_link = $wo->gmail_link ?? '';
        $this->date_started = $wo->date_started?->format('Y-m-d') ?? '';
        $this->day_done = $wo->day_done?->format('Y-m-d') ?? '';
        $this->assigned_name = $wo->assigned_name ?? '';
        $this->amount = $wo->amount !== null ? (string) $wo->amount : '';
        $this->invoice_number = $wo->invoice_number ?? '';
        $this->invoice_link = $wo->invoice_link ?? '';
        $this->notes = $wo->notes ?? '';
        $this->status = $wo->status;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'store_number' => 'required|string|max:20',
            'site' => 'required|string|max:100',
            'service_description' => 'required|string|max:255',
            'work_order_id' => 'required|string|max:50|unique:work_orders,work_order_id'.($this->editingId ? ','.$this->editingId : ''),
            'gmail_link' => 'nullable|url|starts_with:https://',
            'date_started' => 'nullable|date',
            'day_done' => 'nullable|date',
            'assigned_name' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:0',
            'invoice_number' => 'nullable|string|max:50',
            'invoice_link' => 'nullable|url|starts_with:https://',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed',
        ], [
            'store_number.required' => 'El número de tienda es obligatorio.',
            'site.required' => 'El sitio es obligatorio.',
            'service_description.required' => 'La descripción del servicio es obligatoria.',
            'work_order_id.required' => 'El Work Order ID es obligatorio.',
            'work_order_id.unique' => 'Este Work Order ID ya existe.',
            'gmail_link.url' => 'El link de Gmail debe ser una URL válida.',
            'gmail_link.starts_with' => 'El link de Gmail debe comenzar con https://.',
            'invoice_link.url' => 'El link del invoice debe ser una URL válida.',
            'invoice_link.starts_with' => 'El link del invoice debe comenzar con https://.',
            'amount.numeric' => 'El monto debe ser un número.',
            'amount.min' => 'El monto no puede ser negativo.',
            'date_started.date' => 'La fecha de inicio no es válida.',
            'day_done.date' => 'La fecha de finalización no es válida.',
            'status.required' => 'El estado es obligatorio.',
        ]);

        $dayDone = $this->day_done ?: null;

        if ($this->status === 'completed' && ! $dayDone) {
            $dayDone = now()->format('Y-m-d');
        }

        $data = [
            'store_number' => $this->store_number,
            'site' => strtoupper(trim($this->site)),
            'service_description' => $this->service_description,
            'work_order_id' => strtoupper(trim($this->work_order_id)),
            'gmail_link' => $this->gmail_link ?: null,
            'date_started' => $this->date_started ?: null,
            'day_done' => $dayDone,
            'assigned_name' => $this->assigned_name ?: null,
            'amount' => $this->amount !== '' ? $this->amount : null,
            'invoice_number' => $this->invoice_number ?: null,
            'invoice_link' => $this->invoice_link ?: null,
            'notes' => $this->notes ?: null,
            'status' => $this->status,
        ];

        if ($this->editingId) {
            WorkOrder::findOrFail($this->editingId)->update($data);
        } else {
            WorkOrder::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
        unset($this->workOrders, $this->totalBilledMonth, $this->totalBilledYear, $this->completedThisMonth, $this->pendingCount);
    }

    public function delete(int $id): void
    {
        WorkOrder::findOrFail($id)->delete();
        $this->openStatusMenu = null;
        unset($this->workOrders, $this->totalBilledMonth, $this->totalBilledYear, $this->completedThisMonth, $this->pendingCount);
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->openStatusMenu = null;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->store_number = '';
        $this->site = '';
        $this->service_description = '';
        $this->work_order_id = '';
        $this->gmail_link = '';
        $this->date_started = '';
        $this->day_done = '';
        $this->assigned_name = '';
        $this->amount = '';
        $this->invoice_number = '';
        $this->invoice_link = '';
        $this->notes = '';
        $this->status = 'pending';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.work-order-manager');
    }
}
