<?php

namespace Database\Factories\Addresses;

use App\Models\Addresses\Village;
use Database\Factories\Factory;
use Faker\Provider\en_HK\Address;

class VillageFactory extends Factory
{
    protected $model = Village::class;

    public function definition()
    {
        $this->faker->addProvider(new Address($this->faker));

        return [
            'name'     => $this->faker->village,
            'postcode' => $this->faker->postcode(),
        ];
    }
}
