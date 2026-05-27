<?php

namespace App\Modules\Payroll\Livewire;

use App\Modules\Payroll\Models\Worker;
use App\Modules\Payroll\Services\PayrollService;
use Livewire\Attributes\On;
use Livewire\Component;

class WorkerForm extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $base_weekly_pay = '';
    public bool $is_active = true;

    #[On('open-worker-create-modal')]
    public function openCreate(): void
    {
        $this->name = '';
        $this->base_weekly_pay = '';
        $this->is_active = true;
        $this->editingId = null;
        $this->resetValidation();
        $this->showModal = true;
    }

    #[On('open-worker-edit-modal')]
    public function openEdit(int $id): void
    {
        $worker = Worker::findOrFail($id);
        $this->editingId = $id;
        $this->name = $worker->name;
        $this->base_weekly_pay = $worker->base_weekly_pay !== null ? (string) $worker->base_weekly_pay : '';
        $this->is_active = $worker->is_active;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:100',
            'base_weekly_pay' => 'nullable|numeric|min:0',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'base_weekly_pay.numeric' => 'El sueldo debe ser un número.',
            'base_weekly_pay.min' => 'El sueldo no puede ser negativo.',
        ]);

        app(PayrollService::class)->saveWorker([
            'name' => $this->name,
            'base_weekly_pay' => $this->base_weekly_pay,
            'is_active' => $this->is_active,
        ], $this->editingId);

        $this->showModal = false;
        $this->resetValidation();
        $this->dispatch('worker-saved');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.workers.form');
    }
}
