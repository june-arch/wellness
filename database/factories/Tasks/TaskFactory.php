<?php

namespace Database\Factories\Tasks;

use App\Models\Tasks\Task;
use Database\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'name'          => $this->faker->words(rand(2, 4), true),
            'description'   => $this->faker->paragraphs(rand(5, 6), true),
            'is_active'     => true,
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ];
    }
}