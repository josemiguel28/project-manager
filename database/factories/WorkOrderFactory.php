<?php

namespace Database\Factories;

use App\Modules\WorkOrders\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkOrderFactory extends Factory
{
    protected $model = WorkOrder::class;

    public function definition(): array
    {
        $sites = ['BLUFFTON', 'OKATIE', 'HILTON HEAD', 'SAVANNAH', 'BEAUFORT', 'HARDEEVILLE', 'RIDGELAND', 'SUN CITY'];
        $assignees = ['Carlos M.', 'Javier R.', 'Luis P.', 'Pedro A.', 'Miguel S.'];
        $status = $this->faker->randomElement(['pending', 'in_progress', 'completed']);

        $dateStarted = $this->faker->dateTimeBetween('-3 months', 'now');
        $dayDone = $status === 'completed' ? $this->faker->dateTimeBetween($dateStarted, 'now') : null;

        return [
            'store_number' => str_pad($this->faker->numberBetween(1, 99), 3, '0', STR_PAD_LEFT),
            'site' => $this->faker->randomElement($sites),
            'service_description' => $this->faker->randomElement([
                'HVAC filter replacement',
                'Plumbing repair - restroom',
                'Electrical panel inspection',
                'Roof leak repair',
                'Parking lot lighting fix',
                'AC unit maintenance',
                'Door hardware replacement',
                'Ceiling tile replacement',
                'Fire suppression inspection',
                'Pest control treatment',
            ]),
            'work_order_id' => 'WOT' . $this->faker->unique()->numerify('#######'),
            'gmail_link' => $this->faker->boolean(60) ? 'https://mail.google.com/mail/u/0/#inbox/' . $this->faker->lexify('????????') : null,
            'date_started' => $dateStarted->format('Y-m-d'),
            'day_done' => $dayDone ? $dayDone->format('Y-m-d') : null,
            'assigned_name' => $this->faker->randomElement($assignees),
            'amount' => $this->faker->boolean(80) ? $this->faker->randomFloat(2, 150, 4500) : null,
            'invoice_number' => $this->faker->boolean(70) ? 'INV-' . $this->faker->numerify('####') : null,
            'invoice_link' => $this->faker->boolean(50) ? 'https://app.qbo.intuit.com/app/invoice?txnId=' . $this->faker->numerify('#########') : null,
            'notes' => $this->faker->boolean(40) ? $this->faker->sentence(10) : null,
            'status' => $status,
        ];
    }
}
