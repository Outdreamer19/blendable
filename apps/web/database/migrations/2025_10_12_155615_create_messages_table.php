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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('role', ['system', 'user', 'assistant', 'tool']);
            $table->string('model_key')->nullable();
            $table->integer('tokens_in')->default(0);
            $table->integer('tokens_out')->default(0);
            $table->integer('words_out')->default(0);
            $table->json('tool_calls_json')->nullable();
            $table->json('meta_json')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->index(['chat_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
