<?php

namespace Database\Factories;

use App\Models\Receipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceiptFactory extends Factory
{
  protected $model = Receipt::class;

  public function definition()
  {
    return [
      'date' => $this->faker->date(),
      'amount' => $this->faker->randomFloat(2, 0, 10000),
      'reference' => $this->faker->bothify('RCPT-#####'),
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
