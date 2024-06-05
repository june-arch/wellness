<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommonMigration extends Migration
{
    private $settings = 'settings';

    public function up()
    {
        Schema::create($this->settings, function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->foreignId('created_by_id');
            $table->foreignId('updated_by_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->settings);
    }
}
