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
        Schema::create('model_configs', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('model_key')->unique();
            $table->string('display_name');
            $table->integer('context_window')->default(4096);
            $table->decimal('multiplier', 5, 2)->default(1.00);
            $table->decimal('cost_per_1k_tokens_in', 10, 6)->default(0.000000);
            $table->decimal('cost_per_1k_tokens_out', 10, 6)->default(0.000000);
            $table->json('capabilities')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_configs');
    }
};
