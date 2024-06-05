<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DatalogMigration extends Migration
{
    private $datalogs = 'datalogs';

    public function up()
    {
        Schema::create($this->datalogs, function (Blueprint $table) {
            $table->id();
            $table->morphs('loggable', 'datalog');
            $table->string('url_origin')->nullable();
            $table->json('payload');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->datalogs);
    }
}
