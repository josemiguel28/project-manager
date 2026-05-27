<?php

namespace Database\Seeders;

use App\Modules\Payroll\Models\Worker;
use App\Modules\Payroll\Models\WorkerWeekEntry;
use App\Modules\Payroll\Models\WorkWeek;
use Illuminate\Database\Seeder;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $pocho = Worker::create(['name' => 'Pocho', 'base_weekly_pay' => 1000.00, 'is_active' => true]);
        $omar = Worker::create(['name' => 'Omar', 'base_weekly_pay' => 1000.00, 'is_active' => true]);
        $darling = Worker::create(['name' => 'Darling', 'base_weekly_pay' => null, 'is_active' => true]);
        $tito = Worker::create(['name' => 'Tito', 'base_weekly_pay' => null, 'is_active' => true]);

        // Semana 1: 12 - 18 de Mayo 2026
        $week1 = WorkWeek::create([
            'start_date' => '2026-05-12',
            'end_date' => '2026-05-18',
            'notes' => null,
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week1->id,
            'worker_id' => $pocho->id,
            'base_pay' => 1000.00,
            'extra_amount' => 150.00,
            'extra_notes' => 'materiales',
            'payment_status' => 'paid',
            'payment_notes' => null,
            'paid_at' => '2026-05-19',
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week1->id,
            'worker_id' => $omar->id,
            'base_pay' => 1000.00,
            'extra_amount' => null,
            'extra_notes' => null,
            'payment_status' => 'paid',
            'payment_notes' => null,
            'paid_at' => '2026-05-19',
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week1->id,
            'worker_id' => $darling->id,
            'base_pay' => 800.00,
            'extra_amount' => 50.00,
            'extra_notes' => 'gasolina',
            'payment_status' => 'partial',
            'payment_notes' => 'recibos pendientes',
            'paid_at' => '2026-05-19',
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week1->id,
            'worker_id' => $tito->id,
            'base_pay' => 750.00,
            'extra_amount' => null,
            'extra_notes' => null,
            'payment_status' => 'paid',
            'payment_notes' => null,
            'paid_at' => '2026-05-19',
        ]);

        // Semana 2: 19 - 25 de Mayo 2026
        $week2 = WorkWeek::create([
            'start_date' => '2026-05-19',
            'end_date' => '2026-05-25',
            'notes' => 'Semana con proyecto especial en Bluffton',
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week2->id,
            'worker_id' => $pocho->id,
            'base_pay' => 1000.00,
            'extra_amount' => 200.00,
            'extra_notes' => 'horas extra proyecto',
            'payment_status' => 'pending',
            'payment_notes' => null,
            'paid_at' => null,
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week2->id,
            'worker_id' => $omar->id,
            'base_pay' => 1000.00,
            'extra_amount' => null,
            'extra_notes' => null,
            'payment_status' => 'pending',
            'payment_notes' => null,
            'paid_at' => null,
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week2->id,
            'worker_id' => $darling->id,
            'base_pay' => null,
            'extra_amount' => null,
            'extra_notes' => null,
            'payment_status' => 'pending',
            'payment_notes' => null,
            'paid_at' => null,
        ]);

        WorkerWeekEntry::create([
            'work_week_id' => $week2->id,
            'worker_id' => $tito->id,
            'base_pay' => 900.00,
            'extra_amount' => 75.00,
            'extra_notes' => 'devo 5-11',
            'payment_status' => 'refund',
            'payment_notes' => 'devo 5-11',
            'paid_at' => '2026-05-20',
        ]);
    }
}
