<?php

namespace Database\Factories\Companies;

use App\Models\Companies\HealthThreshold;
use Database\Factories\Factory;

class HealthThresholdFactory extends Factory
{
    protected $model = HealthThreshold::class;

    public function definition()
    {
        $name = $this->faker->words(rand(2, 4));

        return [
            'name'          => $this->faker->words(rand(2, 4)),
            'code'          => str($name)->upper()->replace(' ', '_'),
            'target'        => 100,
            'ratio'         => 100,
            'ratio'         => 0.5,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ];
    }
}
