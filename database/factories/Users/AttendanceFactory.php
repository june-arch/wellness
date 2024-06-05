<?php

namespace Database\Factories\Users;

use App\Models\Users\Attendance;
use Database\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        return [
            'status' => collect(['present', 'present', 'present', 'absent', 'sick'])->random(),
            'memo'   => $this->faker->text,
        ];
    }
}
