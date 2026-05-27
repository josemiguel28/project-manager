<?php

namespace App\Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerDayAssignment extends Model
{
    protected $fillable = [
        'worker_week_entry_id',
        'work_date',
        'assignment_notes',
    ];

    protected $casts = [
        'work_date' => 'date',
    ];

    public function workerWeekEntry(): BelongsTo
    {
        return $this->belongsTo(WorkerWeekEntry::class);
    }
}
