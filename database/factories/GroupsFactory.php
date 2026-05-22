<?php

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Service;
use App\Models\Group;

class GroupsFactory extends Factory
{

    protected $model = Group::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'notes' => $this->faker->sentence(),
            'subtotal' => $this->faker->randomFloat(2, 0, 1000),
            'discount' => $this->faker->randomFloat(2, 0, 100),
            'tax_percent' => $this->faker->randomFloat(2, 0, 20),
            'total' => $this->faker->randomFloat(2, 0, 1000),
            'is_active' => $this->faker->boolean(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => null,
        ];
    }
}
