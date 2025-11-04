<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class BillingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Backfill existing users with free plan and reset billing period
        User::whereNull('plan')
            ->orWhere('plan', '')
            ->update([
                'plan' => 'free',
                'token_usage_month' => 0,
                'chat_count_month' => 0,
                'billing_period_start' => now()->startOfMonth(),
            ]);
    }
}
