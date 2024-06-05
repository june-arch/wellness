<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommentMigration extends Migration
{
    private $comments = 'comments';

    public function up()
    {
        Schema::create($this->comments, function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable', 'comment');
            $table->string('name')->nullable();
            $table->text('content');
            $table->string('type');
            $table->integer('rating')->default(0);
            $table->integer('votes')->default(0);
            $table->foreignId('parent_id')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by_id');
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->comments);
    }
}
