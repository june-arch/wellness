<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HealthMigration extends Migration
{
    private $healths = 'healths';

    public function up()
    {
        Schema::create($this->healths, function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->foreignId('user_id');
            $table->integer('step')->default(0);
            $table->float('workout_duration')->default(0);
            $table->float('height')->default(0);
            $table->float('weight')->default(0);
            $table->float('waist_size')->default(0);
            $table->float('hydration')->default(0);
            $table->json('sleep_duration')->default('[]');
            $table->float('systolic')->default(0);
            $table->float('diastolic')->default(0);
            $table->float('blood_glucose')->default(0);
            $table->float('cholesterol')->default(0);
            $table->json('heart_rate')->default('[]');
            $table->float('smoke')->default(0);
            $table->float('point')->default(0);
            $table->float('bmi')->default(0);
            $table->json('stress_level')->default('[]');
            $table->float('master_point')->default(0);
            $table->enum('fitness', ['fit', 'unfit', 'temporary unfit'])->default('fit');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->healths);
    }
}
