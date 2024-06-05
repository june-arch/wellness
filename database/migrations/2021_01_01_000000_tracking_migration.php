<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TrackingMigration extends Migration
{
    private $trackings = 'trackings';

    public function up()
    {
        Schema::create($this->trackings, function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->double('lat');
            $table->double('long');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->trackings);
    }
}
