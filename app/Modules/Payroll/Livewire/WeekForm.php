<?php

namespace App\Modules\Payroll\Livewire;

use App\Modules\Payroll\Services\PayrollService;
use Livewire\Attributes\On;
use Livewire\Component;

class WeekForm extends Component
{
    public bool $showModal = false;

    public string $start_date = '';
    public string $end_date = '';
    public string $notes = '';

    #[On('open-week-create-modal')]
    public function openCreate(): void
    {
        $this->start_date = '';
        $this->end_date = '';
        $this->notes = '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500',
        ], [
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio no es válida.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'end_date.date' => 'La fecha de fin no es válida.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la de inicio.',
        ]);

        if (app(PayrollService::class)->weekHasOverlap($this->start_date, $this->end_date)) {
            $this->addError('start_date', 'Ya existe una semana con un rango de fechas solapado.');

            return;
        }

        app(PayrollService::class)->createWeek([
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'notes' => $this->notes,
        ]);

        $this->showModal = false;
        $this->resetValidation();
        $this->dispatch('week-saved');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.weeks.form');
    }
}
