<?php

namespace Database\Factories\Addresses;

use App\Models\Addresses\City;
use Database\Factories\Factory;

class CityFactory extends Factory
{
    protected $model = City::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->city,
            'province_id' => 1,
        ];
    }
}
