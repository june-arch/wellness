<?php

namespace Database\Factories\Users;

use App\Models\Users\Role;
use Database\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name'          => ucwords($this->faker->words(rand(2, 4), true)),
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ];
    }
}
