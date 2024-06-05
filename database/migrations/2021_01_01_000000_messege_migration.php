<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MessegeMigration extends Migration
{
    private $messeges = 'messeges';

    public function up()
    {
        Schema::create($this->messeges, function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->text('content');
            $table->foreignId('user_id')->nullable();
            $table->foreignId('parent_id')->nullable();
            $table->boolean('is_read')->default(0);
            $table->foreignId('read_by_id')->nullable();
            $table->boolean('is_replied')->default(0);
            $table->foreignId('replied_by_id')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->messeges);
    }
}
