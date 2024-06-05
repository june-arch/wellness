<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AttendanceMigration extends Migration
{
    private $attendances = 'attendances';

    public function up()
    {
        Schema::create($this->attendances, function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->enum('status', ['present', 'absent', 'sick']);
            $table->text('memo')->nullable();
            $table->date('date')->useCurrent();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->attendances);
    }
}
