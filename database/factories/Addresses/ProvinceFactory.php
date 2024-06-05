<?php

namespace Database\Factories\Addresses;

use App\Models\Addresses\Province;
use Database\Factories\Factory;

class ProvinceFactory extends Factory
{
    protected $model = Province::class;

    public function definition()
    {
        return [
            'name' => $this->faker->state,
        ];
    }
}
