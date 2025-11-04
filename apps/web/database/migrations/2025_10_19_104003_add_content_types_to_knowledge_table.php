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
        Schema::table('knowledge', function (Blueprint $table) {
            // Add fields for better content organization
            $table->string('author')->nullable()->after('source');
            $table->string('category')->nullable()->after('author');
            $table->integer('importance_score')->default(5)->after('category'); // 1-10 scale
            $table->json('tags')->nullable()->after('importance_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledge', function (Blueprint $table) {
            $table->dropColumn(['author', 'category', 'importance_score', 'tags']);
        });
    }
};
