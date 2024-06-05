<?php

namespace Database\Factories\Users;

use App\Models\Users\Permission;
use Database\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'gate' => collect(explode(' ', $this->faker->words(rand(2, 5))))->take(3)->join('-'),
        ];
    }
}
