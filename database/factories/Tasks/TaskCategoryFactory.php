<?php

namespace Database\Factories\Tasks;

use App\Models\Tasks\TaskCategory;
use Database\Factories\Factory;

class TaskCategoryFactory extends Factory
{
    protected $model = TaskCategory::class;

    public function definition()
    {
        return [
            'name'          => $this->faker->words(rand(2, 4), true),
            'description'   => $this->faker->paragraphs(rand(1, 4), true),
            'created_by_id' => 1,
            'updated_by_id' => 1,
        ];
    }
}
