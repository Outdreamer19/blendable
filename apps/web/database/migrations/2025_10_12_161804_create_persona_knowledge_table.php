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
        Schema::create('persona_knowledge', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->constrained()->cascadeOnDelete();
            $table->foreignId('knowledge_id')->constrained()->cascadeOnDelete();
            $table->decimal('weight', 3, 2)->default(1.0);
            $table->timestamps();

            $table->unique(['persona_id', 'knowledge_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_knowledge');
    }
};
