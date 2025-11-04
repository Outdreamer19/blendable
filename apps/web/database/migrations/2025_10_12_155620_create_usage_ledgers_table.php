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
        Schema::create('usage_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('workspace_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('message_id')->nullable()->constrained()->nullOnDelete();
            $table->string('model_key');
            $table->integer('tokens_in')->default(0);
            $table->integer('tokens_out')->default(0);
            $table->integer('words_debited')->default(0);
            $table->decimal('multiplier', 5, 2)->default(1.00);
            $table->decimal('cost_usd', 10, 4)->default(0.0000);
            $table->date('usage_date');
            $table->timestamps();

            $table->index(['user_id', 'usage_date']);
            $table->index(['team_id', 'usage_date']);
            $table->index(['workspace_id', 'usage_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usage_ledgers');
    }
};
