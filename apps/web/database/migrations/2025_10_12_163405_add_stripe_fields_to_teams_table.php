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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->unique();
            $table->string('stripe_status')->nullable();
            $table->string('stripe_plan')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->integer('words_used_this_month')->default(0);
            $table->integer('api_calls_used_this_month')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_id',
                'stripe_status',
                'stripe_plan',
                'trial_ends_at',
                'words_used_this_month',
                'api_calls_used_this_month',
            ]);
        });
    }
};
