<?php

namespace App\Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    protected $fillable = [
        'name',
        'base_weekly_pay',
        'is_active',
    ];

    protected $casts = [
        'base_weekly_pay' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function weekEntries(): HasMany
    {
        return $this->hasMany(WorkerWeekEntry::class);
    }
}
