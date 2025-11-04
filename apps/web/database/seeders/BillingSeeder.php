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
        // Reset billing period for existing users (no free plan assignment)
        User::update([
            'token_usage_month' => 0,
            'chat_count_month' => 0,
            'billing_period_start' => now()->startOfMonth(),
        ]);
    }
}
