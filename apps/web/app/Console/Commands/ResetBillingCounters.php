<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetBillingCounters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:reset-counters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset monthly billing counters for all users on the first day of the month';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Resetting monthly billing counters...');

        $updated = User::query()
            ->where('billing_period_start', '<', now()->startOfMonth())
            ->update([
                'token_usage_month' => 0,
                'chat_count_month' => 0,
                'billing_period_start' => now()->startOfMonth(),
            ]);

        $this->info("Reset billing counters for {$updated} users.");

        // Log the reset for analytics
        \Log::info('Monthly billing counters reset', [
            'users_updated' => $updated,
            'reset_date' => now()->startOfMonth(),
        ]);

        return Command::SUCCESS;
    }
}
