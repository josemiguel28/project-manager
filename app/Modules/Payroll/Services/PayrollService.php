<?php

namespace App\Modules\Payroll\Services;

use App\Modules\Payroll\Models\Worker;
use App\Modules\Payroll\Models\WorkerDayAssignment;
use App\Modules\Payroll\Models\WorkerWeekEntry;
use App\Modules\Payroll\Models\WorkWeek;
use Illuminate\Database\Eloquent\Collection;

class PayrollService
{
    public function getWorkers(): Collection
    {
        return Worker::orderBy('name')->get();
    }

    public function saveWorker(array $data, ?int $id): Worker
    {
        $payload = [
            'name' => trim($data['name']),
            'base_weekly_pay' => isset($data['base_weekly_pay']) && $data['base_weekly_pay'] !== '' ? $data['base_weekly_pay'] : null,
            'is_active' => $data['is_active'] ?? true,
        ];

        if ($id) {
            $worker = Worker::findOrFail($id);
            $worker->update($payload);

            return $worker;
        }

        return Worker::create($payload);
    }

    public function toggleWorkerActive(int $id): void
    {
        $worker = Worker::findOrFail($id);
        $worker->update(['is_active' => ! $worker->is_active]);
    }

    public function getWeeks(): Collection
    {
        return WorkWeek::with('entries')->orderByDesc('start_date')->get();
    }

    public function weekHasOverlap(string $startDate, string $endDate, ?int $excludeId = null): bool
    {
        return WorkWeek::when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->where('start_date', '<=', $endDate)
            ->where('end_date', '>=', $startDate)
            ->exists();
    }

    public function createWeek(array $data): WorkWeek
    {
        $week = WorkWeek::create([
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'notes' => $data['notes'] ?: null,
        ]);

        Worker::where('is_active', true)->each(function (Worker $worker) use ($week) {
            WorkerWeekEntry::create([
                'work_week_id' => $week->id,
                'worker_id' => $worker->id,
                'base_pay' => $worker->base_weekly_pay,
                'extra_amount' => null,
                'extra_notes' => null,
                'payment_status' => 'pending',
                'payment_notes' => null,
                'paid_at' => null,
            ]);
        });

        return $week;
    }

    public function saveAssignment(int $entryId, string $workDate, string $notes): void
    {
        WorkerDayAssignment::upsert(
            [
                [
                    'worker_week_entry_id' => $entryId,
                    'work_date' => $workDate,
                    'assignment_notes' => $notes ?: null,
                ],
            ],
            uniqueBy: ['worker_week_entry_id', 'work_date'],
            update: ['assignment_notes', 'updated_at'],
        );
    }

    public function saveEntry(int $id, array $data): void
    {
        $entry = WorkerWeekEntry::findOrFail($id);

        $basePay = $data['base_pay'] !== '' ? $data['base_pay'] : null;
        $extraAmount = $data['extra_amount'] !== '' ? $data['extra_amount'] : null;
        $paidAt = $data['paid_at'] !== '' ? $data['paid_at'] : null;

        if ($data['payment_status'] !== 'pending' && ! $paidAt) {
            $paidAt = now()->format('Y-m-d');
        }

        $entry->update([
            'base_pay' => $basePay,
            'extra_amount' => $extraAmount,
            'extra_notes' => $data['extra_notes'] ?: null,
            'payment_status' => $data['payment_status'],
            'payment_notes' => $data['payment_notes'] ?: null,
            'paid_at' => $paidAt,
        ]);
    }
}
