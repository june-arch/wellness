<?php

namespace Database\Factories\Users;

use App\Models\Users\User;
use Database\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name'          => $this->faker->name,
            'email'         => $this->faker->email,
            'phone'         => $this->faker->phoneNumber,
            'password'      => 'password',
            'bio'           => $this->faker->paragraphs(rand(1, 5), true),
            'is_active'     => 1,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ];
    }
}
