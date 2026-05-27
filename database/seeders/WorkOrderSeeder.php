<?php

namespace Database\Seeders;

use App\Modules\WorkOrders\Models\WorkOrder;
use Illuminate\Database\Seeder;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        WorkOrder::create([
            'store_number' => '033',
            'site' => 'BLUFFTON',
            'service_description' => 'AC unit maintenance and filter replacement',
            'work_order_id' => 'WOT0141360',
            'gmail_link' => 'https://mail.google.com/mail/u/0/#inbox/abc123def456',
            'date_started' => '2026-05-01',
            'day_done' => '2026-05-03',
            'assigned_name' => 'Carlos M.',
            'amount' => 850.00,
            'invoice_number' => 'INV-1042',
            'invoice_link' => 'https://app.qbo.intuit.com/app/invoice?txnId=123456789',
            'notes' => 'Cliente solicitó revisión adicional de ductos.',
            'status' => 'completed',
        ]);

        WorkOrder::create([
            'store_number' => '061',
            'site' => 'OKATIE',
            'service_description' => 'Plomería - reparación de baño principal',
            'work_order_id' => 'WOT0141361',
            'gmail_link' => 'https://mail.google.com/mail/u/0/#inbox/xyz789uvw012',
            'date_started' => '2026-05-05',
            'day_done' => null,
            'assigned_name' => 'Javier R.',
            'amount' => 1200.00,
            'invoice_number' => null,
            'invoice_link' => null,
            'notes' => null,
            'status' => 'in_progress',
        ]);

        WorkOrder::create([
            'store_number' => '027',
            'site' => 'HILTON HEAD',
            'service_description' => 'Inspección panel eléctrico',
            'work_order_id' => 'WOT0141362',
            'gmail_link' => null,
            'date_started' => '2026-05-10',
            'day_done' => null,
            'assigned_name' => 'Luis P.',
            'amount' => null,
            'invoice_number' => null,
            'invoice_link' => null,
            'notes' => 'Pendiente aprobación de propietario.',
            'status' => 'pending',
        ]);

        WorkOrder::create([
            'store_number' => '045',
            'site' => 'SAVANNAH',
            'service_description' => 'Reparación de techo - fuga de agua',
            'work_order_id' => 'WOT0141363',
            'gmail_link' => 'https://mail.google.com/mail/u/0/#inbox/lmn345opq678',
            'date_started' => '2026-04-20',
            'day_done' => '2026-04-22',
            'assigned_name' => 'Pedro A.',
            'amount' => 2350.50,
            'invoice_number' => 'INV-1038',
            'invoice_link' => 'https://app.qbo.intuit.com/app/invoice?txnId=987654321',
            'notes' => null,
            'status' => 'completed',
        ]);

        WorkOrder::create([
            'store_number' => '033',
            'site' => 'BLUFFTON',
            'service_description' => 'Sustitución de iluminación en estacionamiento',
            'work_order_id' => 'WOT0141364',
            'gmail_link' => null,
            'date_started' => '2026-05-12',
            'day_done' => null,
            'assigned_name' => 'Miguel S.',
            'amount' => 680.00,
            'invoice_number' => null,
            'invoice_link' => null,
            'notes' => null,
            'status' => 'in_progress',
        ]);

        WorkOrder::create([
            'store_number' => '078',
            'site' => 'BEAUFORT',
            'service_description' => 'Tratamiento control de plagas',
            'work_order_id' => 'WOT0141365',
            'gmail_link' => 'https://mail.google.com/mail/u/0/#inbox/rst901uvw234',
            'date_started' => '2026-04-15',
            'day_done' => '2026-04-15',
            'assigned_name' => 'Carlos M.',
            'amount' => 425.00,
            'invoice_number' => 'INV-1035',
            'invoice_link' => null,
            'notes' => 'Tratamiento trimestral programado.',
            'status' => 'completed',
        ]);

        WorkOrder::create([
            'store_number' => '061',
            'site' => 'OKATIE',
            'service_description' => 'Reemplazo de baldosas en techo',
            'work_order_id' => 'WOT0141366',
            'gmail_link' => null,
            'date_started' => null,
            'day_done' => null,
            'assigned_name' => null,
            'amount' => null,
            'invoice_number' => null,
            'invoice_link' => null,
            'notes' => 'Esperando materiales.',
            'status' => 'pending',
        ]);

        WorkOrder::create([
            'store_number' => '012',
            'site' => 'HARDEEVILLE',
            'service_description' => 'Mantenimiento sistema de supresión de incendios',
            'work_order_id' => 'WOT0141367',
            'gmail_link' => 'https://mail.google.com/mail/u/0/#inbox/efg567hij890',
            'date_started' => '2026-05-08',
            'day_done' => '2026-05-09',
            'assigned_name' => 'Javier R.',
            'amount' => 1875.00,
            'invoice_number' => 'INV-1041',
            'invoice_link' => 'https://app.qbo.intuit.com/app/invoice?txnId=456789123',
            'notes' => 'Inspección anual requerida por seguro.',
            'status' => 'completed',
        ]);

        WorkOrder::factory(4)->create();
    }
}
