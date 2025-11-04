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
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('free');
            $table->bigInteger('token_usage_month')->default(0);
            $table->integer('chat_count_month')->default(0);
            $table->date('billing_period_start')->default(now()->startOfMonth());
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['plan', 'token_usage_month', 'chat_count_month', 'billing_period_start']);
        });
    }
};
