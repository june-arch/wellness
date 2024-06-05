<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $chats      = 'chats';
    private $chat_items = 'chat_items';
    private $user_chats = 'user_chats';

    public function up(): void
    {
        Schema::create($this->chats, function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create($this->chat_items, function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id');
            $table->foreignId('user_id');
            $table->text('message');
            $table->timestamp('date')->useCurrent();
        });

        Schema::create($this->user_chats, function (Blueprint $table) {
            $table->foreignId('chat_id');
            $table->foreignId('user_id');
            $table->timestamp('last_read_at')->useCurrent();
            $table->timestamp('last_deleted_at')->nullable();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists($this->user_chats);
        Schema::dropIfExists($this->chat_items);
        Schema::dropIfExists($this->chats);
    }
};
