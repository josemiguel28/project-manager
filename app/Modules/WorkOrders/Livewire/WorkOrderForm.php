<?php

namespace App\Modules\WorkOrders\Livewire;

use App\Modules\WorkOrders\Models\WorkOrder;
use App\Modules\WorkOrders\Services\WorkOrderService;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkOrderForm extends Component
{
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

    #[On('open-create-modal')]
    public function openCreate(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    #[On('open-edit-modal')]
    public function openEdit(int $id): void
    {
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

        app(WorkOrderService::class)->save($this->formData(), $this->editingId);

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('work-order-saved');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function formData(): array
    {
        return [
            'store_number' => $this->store_number,
            'site' => $this->site,
            'service_description' => $this->service_description,
            'work_order_id' => $this->work_order_id,
            'gmail_link' => $this->gmail_link,
            'date_started' => $this->date_started,
            'day_done' => $this->day_done,
            'assigned_name' => $this->assigned_name,
            'amount' => $this->amount,
            'invoice_number' => $this->invoice_number,
            'invoice_link' => $this->invoice_link,
            'notes' => $this->notes,
            'status' => $this->status,
        ];
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
        return view('livewire.work-orders.form');
    }
}
