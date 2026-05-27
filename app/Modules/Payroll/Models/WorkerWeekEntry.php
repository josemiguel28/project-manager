<?php

namespace App\Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkerWeekEntry extends Model
{
    protected $fillable = [
        'work_week_id',
        'worker_id',
        'base_pay',
        'extra_amount',
        'extra_notes',
        'payment_status',
        'payment_notes',
        'paid_at',
    ];

    protected $casts = [
        'base_pay' => 'decimal:2',
        'extra_amount' => 'decimal:2',
        'paid_at' => 'date',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function workWeek(): BelongsTo
    {
        return $this->belongsTo(WorkWeek::class);
    }

    public function dayAssignments(): HasMany
    {
        return $this->hasMany(WorkerDayAssignment::class);
    }

    public function getTotal(): ?float
    {
        if ($this->base_pay === null && $this->extra_amount === null) {
            return null;
        }

        return (float) ($this->base_pay ?? 0) + (float) ($this->extra_amount ?? 0);
    }
}
