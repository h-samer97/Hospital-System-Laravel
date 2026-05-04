<?php

namespace Database\Factories;

use App\Models\Groups;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupsFactory extends Factory
{
    protected $model = Groups::class;

    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 1200);
        $discount = $this->faker->randomFloat(2, 0, $subtotal * 0.2);
        $afterDiscount = round($subtotal - $discount, 2);
        $taxRates = ['0%', '5%', '10%', '15%'];
        $taxRate = $taxRates[array_rand($taxRates)];
        $taxAmount = round($afterDiscount * ((int) trim($taxRate, '%') / 100), 2);
        $totalWithTax = round($afterDiscount + $taxAmount, 2);

        return [
            'name' => $this->faker->sentence(3),
            'notes' => $this->faker->optional()->sentence(),
            'total_before_discount' => $subtotal,
            'discount_value' => $discount,
            'total_after_discount' => $afterDiscount,
            'tax_rate' => $taxRate,
            'total_with_tax' => $totalWithTax,
        ];
    }
}
