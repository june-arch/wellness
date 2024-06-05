<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddressMigration extends Migration
{
    private $addresses = 'addresses';

    public function up()
    {
        Schema::create($this->addresses, function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('line_1')->nullable();
            $table->string('line_2')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_2')->nullable();
            $table->string('email')->nullable();
            $table->string('postcode', 5)->nullable();
            $table->boolean('is_primary')->default(0);

            $table->morphs('addressable', 'address');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->addresses);
    }
}
