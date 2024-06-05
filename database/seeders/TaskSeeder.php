<?php

namespace Database\Seeders;

use App\Models\Tasks\Task;
use App\Models\Tasks\TaskCategory;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    protected $totalTasks = 120;

    public function run()
    {
        $categories = TaskCategory::factory()->count(4)->create();

        for ($num = 0; $num <= $this->totalTasks; $num++) {
            Task::factory()->create([
                'category_id' => $categories->random(1)->first()->id,
            ]);
        }
    }
}
