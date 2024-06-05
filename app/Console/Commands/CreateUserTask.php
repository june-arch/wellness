<?php

namespace App\Console\Commands;

use App\Repositories\Tasks\UserTaskRepository;
use Illuminate\Console\Command;

class CreateUserTask extends Command
{
    protected $signature = 'task:generate {time=daily}';

    protected $description = 'Create new daily task for user';

    public function handle()
    {
        if ($this->argument('time') === 'weekly') {
            $this->warn("Creating weekly tasks....");
            UserTaskRepository::generateWeeklyTasks();
            $this->info("Success creating weekly tasks");
        }

        if ($this->argument('time') === 'daily') {
            $this->warn("Creating daily tasks....");
            UserTaskRepository::generateDailyTasks();
            $this->info("Success creating daily tasks");
        }

    }
}
