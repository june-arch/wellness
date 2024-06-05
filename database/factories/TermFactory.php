<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermFactory extends Factory
{
    protected $model = Term::class;

    public function definition()
    {
        $name = $this->faker->words(rand(1, 3), true);

        return [
            'name' => ucwords($name),
            'slug' => Str::slug($name) . '-' . rand(1000, 9999),
            'content' => $this->faker->paragraphs(rand(1, 4), true),
        ];
    }
}
