<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Users\Admin;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition()
    {
        return [
            'name'           => $this->faker->name,
            'content'        => $this->faker->paragraphs(rand(1, 2), true),
            'rating'         => rand(1, 5),
            'approved_at'    => now(),
            'approved_by_id' => Admin::inRandomOrder()->first()->id,
        ];
    }
}
