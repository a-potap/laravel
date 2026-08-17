<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_read_receipts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_message_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('read_at')->useCurrent();

            $table->foreign('chat_message_id')->references('id')->on('chat_messages')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['chat_message_id', 'user_id']);
            $table->index('chat_message_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_read_receipts');
    }
};
