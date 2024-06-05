<?php

namespace Database\Factories\Addresses;

use App\Models\Addresses\Address;
use Database\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition()
    {
        return [
            'name'     => $this->faker->words(1, true),
            'receiver' => $this->faker->name,
            'line_1'   => $this->faker->secondaryAddress,
            'line_2'   => $this->faker->streetAddress(),
            'country'  => $this->faker->country(),
            'phone'    => $this->faker->phoneNumber(),
            'phone_2'  => $this->faker->phoneNumber(),
            'email'    => $this->faker->email(),
            'postcode' => $this->faker->postcode(),
        ];
    }
}
