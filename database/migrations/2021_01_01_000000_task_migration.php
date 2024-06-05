<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TaskMigration extends Migration
{
    private $tasks           = 'tasks';
    private $task_categories = 'task_categories';
    private $task_tags       = 'task_tags';
    private $task_taggables  = 'task_taggables';
    private $user_tasks      = 'user_tasks';

    public function up()
    {
        Schema::create($this->tasks, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->boolean('is_active')->useCurrent();
            $table->foreignId('category_id');
            $table->foreignId('company_id')->nullable();
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();

            $table->index(['name', 'company_id', 'category_id'], 'unique_task_name');
        });

        Schema::create($this->task_categories, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('created_by_id')->nullable();
            $table->foreignId('updated_by_id')->nullable();
            $table->timestamps();

            $table->index(['name', 'company_id'], 'task_category_name');
        });

        Schema::create($this->task_tags, function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->nullable();
            $table->timestamps();
            $table->index(['name', 'company_id'], 'task_tag_name');
        });

        Schema::create($this->task_taggables, function (Blueprint $table) {
            $table->foreignId('task_tag_id');
            $table->morphs('task_taggable', 'task_taggable');
            $table->timestamps();
        });

        Schema::create($this->user_tasks, function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id');
            $table->foreignId('user_id');
            $table->boolean('is_complete')->default(false);
            $table->date('date');
            $table->integer('time')->default(1);
        });

    }

    public function down()
    {
        Schema::dropIfExists($this->user_tasks);
        Schema::dropIfExists($this->task_tags);
        Schema::dropIfExists($this->task_categories);
        Schema::dropIfExists($this->tasks);
    }
}
