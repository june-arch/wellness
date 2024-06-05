<?php

namespace App\Console\Commands;

use App\Repositories\Healths\HealthRepository;
use Illuminate\Console\Command;

class CreateUserHealth extends Command
{
    protected $signature = 'health:generate';

    protected $description = 'Create new daily task for user';

    public function handle()
    {
        $this->warn("Creating weekly tasks....");
        HealthRepository::generateDailyData();
        $this->info("Success creating weekly tasks");
    }
}
