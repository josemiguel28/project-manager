<?php

namespace App\Modules\Payroll\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkWeek extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(WorkerWeekEntry::class)->with('worker')->orderBy('worker_id');
    }

    public function formattedRange(): string
    {
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        $month = $months[$this->start_date->month];

        return $this->start_date->day.' - '.$this->end_date->day.' de '.$month.' '.$this->start_date->year;
    }
}
