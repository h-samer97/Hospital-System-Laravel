<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\PaymentAccount;
use App\Models\PrintLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PrintLog>
 */
class PrintLogFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'printable_type' => PaymentAccount::class,
            'printable_id' => PaymentAccount::faktory(),
            'admin_id'      => Admin::factory(),
            'action'        => $this->faker->randomElement(['view', 'download']),
            'ip_address'    => $this->faker->ipv4(),
            'user_agent'    => $this->faker->userAgent()
        ];
    }
     public function download(): static
    {
        return $this->state(['action' => 'download']);
    } 
}
