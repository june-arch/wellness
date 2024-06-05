<?php

namespace Database\Factories\Addresses;

use App\Models\Addresses\District;
use Database\Factories\Factory;
use Faker\Provider\ne_NP\Address;
use Illuminate\Support\Str;

class DistrictFactory extends Factory
{
    protected $model = District::class;

    public function definition()
    {
        $this->faker->addProvider(new Address($this->faker));
        return [
            'name' => $this->faker->district,
            'code' => Str::random(),
        ];
    }
}
