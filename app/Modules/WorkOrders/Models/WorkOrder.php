<?php

namespace App\Modules\WorkOrders\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_number',
        'site',
        'service_description',
        'work_order_id',
        'gmail_link',
        'date_started',
        'day_done',
        'assigned_name',
        'amount',
        'invoice_number',
        'invoice_link',
        'notes',
        'status',
    ];

    protected $casts = [
        'date_started' => 'date',
        'day_done' => 'date',
        'amount' => 'decimal:2',
    ];

    protected static function newFactory()
    {
        return \Database\Factories\WorkOrderFactory::new();
    }
}
