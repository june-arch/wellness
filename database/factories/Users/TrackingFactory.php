<?php

namespace Database\Factories\Users;

use App\Models\Users\Tracking;
use Database\Factories\Factory;

class TrackingFactory extends Factory
{
    protected $model = Tracking::class;

    public function definition()
    {
        return [
            'lat'  => $this->faker->longitude(-6.359, -6.09),
            'long' => $this->faker->longitude(106.68, 106.97),
        ];
    }
}
