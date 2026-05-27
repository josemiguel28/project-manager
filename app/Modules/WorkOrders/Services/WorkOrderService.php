<?php

namespace App\Modules\WorkOrders\Services;

use App\Modules\WorkOrders\Models\WorkOrder;
use Illuminate\Database\Eloquent\Collection;

class WorkOrderService
{
    public function getFiltered(string $search, string $statusFilter): Collection
    {
        return WorkOrder::query()
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('store_number', 'like', '%'.$search.'%')
                        ->orWhere('site', 'like', '%'.$search.'%')
                        ->orWhere('work_order_id', 'like', '%'.$search.'%')
                        ->orWhere('assigned_name', 'like', '%'.$search.'%');
                });
            })
            ->when($statusFilter !== 'all', fn ($q) => $q->where('status', $statusFilter))
            ->orderByDesc('created_at')
            ->get();
    }

    public function save(array $data, ?int $id): WorkOrder
    {
        $dayDone = $data['day_done'] ?: null;

        if ($data['status'] === 'completed' && ! $dayDone) {
            $dayDone = now()->format('Y-m-d');
        }

        $payload = [
            'store_number' => $data['store_number'],
            'site' => strtoupper(trim($data['site'])),
            'service_description' => $data['service_description'],
            'work_order_id' => strtoupper(trim($data['work_order_id'])),
            'gmail_link' => $data['gmail_link'] ?: null,
            'date_started' => $data['date_started'] ?: null,
            'day_done' => $dayDone,
            'assigned_name' => $data['assigned_name'] ?: null,
            'amount' => $data['amount'] !== '' ? $data['amount'] : null,
            'invoice_number' => $data['invoice_number'] ?: null,
            'invoice_link' => $data['invoice_link'] ?: null,
            'notes' => $data['notes'] ?: null,
            'status' => $data['status'],
        ];

        if ($id) {
            $workOrder = WorkOrder::findOrFail($id);
            $workOrder->update($payload);

            return $workOrder;
        }

        return WorkOrder::create($payload);
    }

    public function delete(int $id): void
    {
        WorkOrder::findOrFail($id)->delete();
    }

    public function quickChangeStatus(int $id, string $status): void
    {
        $workOrder = WorkOrder::findOrFail($id);
        $data = ['status' => $status];

        if ($status === 'completed' && ! $workOrder->day_done) {
            $data['day_done'] = now()->format('Y-m-d');
        }

        $workOrder->update($data);
    }

    public function totalBilledMonth(): string
    {
        $total = WorkOrder::query()
            ->where('status', 'completed')
            ->whereMonth('day_done', now()->month)
            ->whereYear('day_done', now()->year)
            ->sum('amount');

        return '$'.number_format($total, 2);
    }

    public function totalBilledYear(): string
    {
        $total = WorkOrder::query()
            ->where('status', 'completed')
            ->whereYear('day_done', now()->year)
            ->sum('amount');

        return '$'.number_format($total, 2);
    }

    public function completedThisMonth(): int
    {
        return WorkOrder::query()
            ->where('status', 'completed')
            ->whereMonth('day_done', now()->month)
            ->whereYear('day_done', now()->year)
            ->count();
    }

    public function pendingCount(): int
    {
        return WorkOrder::query()
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();
    }
}
