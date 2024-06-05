<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileMigration extends Migration
{
    private $files     = 'files';
    private $fileables = 'fileables';

    public function up()
    {
        Schema::create($this->files, function (Blueprint $table) {
            $table->id();
            $table->string('folder')->nullable();
            $table->string('name');
            $table->string('path');
            $table->string('mime_type');
            $table->string('disk')->default('upload');
            $table->boolean('optimized')->default(false);
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedInteger('order_column')->default(1);
            $table->timestamps();

            $table->unique(['folder', 'path'], 'unique_files');
        });

        Schema::create($this->fileables, function (Blueprint $table) {
            $table->foreignId('file_id');
            $table->foreignId('fileable_id');
            $table->string('fileable_type');
            $table->index(['file_id', 'fileable_id', 'fileable_type'], 'fileable');
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->files);
        Schema::dropIfExists($this->fileables);
    }
}
