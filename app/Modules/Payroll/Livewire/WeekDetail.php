<?php

namespace App\Modules\Payroll\Livewire;

use App\Modules\Payroll\Models\WorkWeek;
use App\Modules\Payroll\Services\PayrollService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WeekDetail extends Component
{
    public int $weekId;
    public array $entries = [];
    public array $assignments = [];

    public function mount(WorkWeek $week): void
    {
        $this->weekId = $week->id;
        $this->loadEntries();
    }

    protected function loadEntries(): void
    {
        $week = WorkWeek::with('entries.worker', 'entries.dayAssignments')->findOrFail($this->weekId);

        $dates = [];
        $current = $week->start_date;
        while ($current->lte($week->end_date)) {
            $dates[] = $current->format('Y_m_d');
            $current = $current->addDay();
        }

        foreach ($week->entries as $entry) {
            $rawNotes = $entry->extra_notes ?? '';
            $extraCategories = [];
            $extraFreeNote = '';
            if ($rawNotes !== '') {
                $decoded = json_decode($rawNotes, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && array_key_exists('categories', $decoded)) {
                    $extraCategories = $decoded['categories'] ?? [];
                    $extraFreeNote = $decoded['note'] ?? '';
                } else {
                    $extraFreeNote = $rawNotes;
                }
            }

            $this->entries[$entry->id] = [
                'worker_name'      => $entry->worker->name,
                'base_pay'         => $entry->base_pay !== null ? (string) $entry->base_pay : '',
                'extra_amount'     => $entry->extra_amount !== null ? (string) $entry->extra_amount : '',
                'extra_categories' => $extraCategories,
                'extra_free_note'  => $extraFreeNote,
                'payment_status'   => $entry->payment_status,
                'payment_notes'    => $entry->payment_notes ?? '',
                'paid_at'          => $entry->paid_at?->format('Y-m-d') ?? '',
            ];

            $this->assignments[$entry->id] = array_fill_keys($dates, '');

            foreach ($entry->dayAssignments as $a) {
                $key = $a->work_date->format('Y_m_d');
                if (array_key_exists($key, $this->assignments[$entry->id])) {
                    $this->assignments[$entry->id][$key] = $a->assignment_notes ?? '';
                }
            }
        }
    }

    #[Computed]
    public function week(): WorkWeek
    {
        return WorkWeek::findOrFail($this->weekId);
    }

    #[Computed]
    public function weekDays(): array
    {
        $week = WorkWeek::findOrFail($this->weekId);
        $days = [];
        $current = $week->start_date;
        while ($current->lte($week->end_date)) {
            $days[] = $current;
            $current = $current->addDay();
        }

        return $days;
    }

    public function updated(string $name, $value): void
    {
        $parts = explode('.', $name);

        if (str_starts_with($name, 'entries.')) {
            if (count($parts) !== 3) {
                return;
            }
            [, $id, $field] = $parts;
            $allowed = ['base_pay', 'extra_amount', 'extra_free_note', 'payment_status', 'payment_notes', 'paid_at'];
            if (in_array($field, $allowed)) {
                $this->saveEntry((int) $id);
            }

            return;
        }

        if (str_starts_with($name, 'assignments.')) {
            if (count($parts) !== 3) {
                return;
            }
            [, $entryId, $dateKey] = $parts;
            $this->saveDayAssignment((int) $entryId, str_replace('_', '-', $dateKey));
        }
    }

    public function toggleCategory(int $entryId, string $category): void
    {
        if (! isset($this->entries[$entryId])) {
            return;
        }

        $categories = $this->entries[$entryId]['extra_categories'] ?? [];
        if (in_array($category, $categories, true)) {
            $this->entries[$entryId]['extra_categories'] = array_values(
                array_filter($categories, fn ($c) => $c !== $category)
            );
        } else {
            $this->entries[$entryId]['extra_categories'][] = $category;
        }

        $this->saveEntry($entryId);
    }

    protected function saveEntry(int $id): void
    {
        if (! isset($this->entries[$id])) {
            return;
        }

        $data = $this->entries[$id];
        $paidAt = $data['paid_at'] !== '' ? $data['paid_at'] : null;

        if ($data['payment_status'] !== 'pending' && ! $paidAt) {
            $paidAt = now()->format('Y-m-d');
            $this->entries[$id]['paid_at'] = $paidAt;
        }

        $categories = $data['extra_categories'] ?? [];
        $freeNote = $data['extra_free_note'] ?? '';
        $extraNotes = (! empty($categories) || $freeNote !== '')
            ? json_encode(['categories' => $categories, 'note' => $freeNote], JSON_UNESCAPED_UNICODE)
            : '';

        app(PayrollService::class)->saveEntry($id, array_merge($data, [
            'paid_at'     => $paidAt ?? '',
            'extra_notes' => $extraNotes,
        ]));
    }

    protected function saveDayAssignment(int $entryId, string $workDate): void
    {
        if (! isset($this->assignments[$entryId])) {
            return;
        }

        $dateKey = str_replace('-', '_', $workDate);
        $notes = $this->assignments[$entryId][$dateKey] ?? '';

        app(PayrollService::class)->saveAssignment($entryId, $workDate, $notes);
    }

    public function render()
    {
        return view('livewire.weeks.detail');
    }
}
