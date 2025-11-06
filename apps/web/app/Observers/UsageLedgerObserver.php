<?php

namespace App\Observers;

use App\Models\UsageLedger;
use App\Models\User;
use Carbon\Carbon;

class UsageLedgerObserver
{
    /**
     * Handle the UsageLedger "created" event.
     */
    public function created(UsageLedger $usageLedger): void
    {
        $user = $usageLedger->user;
        
        if (!$user) {
            return;
        }

        // Check if we need to reset counters (new billing period)
        $currentMonthStart = now()->startOfMonth();
        if (!$user->billing_period_start || $user->billing_period_start->lt($currentMonthStart)) {
            $user->update([
                'token_usage_month' => 0,
                'chat_count_month' => 0,
                'billing_period_start' => $currentMonthStart,
            ]);
        }

        // Update user counters for current month
        // Only count usage from the current billing period
        if ($usageLedger->usage_date && $usageLedger->usage_date->gte($user->billing_period_start ?? $currentMonthStart)) {
            // Increment token usage (using words_debited as proxy for tokens)
            // For more accurate tracking, you might want to use tokens_in + tokens_out
            $user->increment('token_usage_month', $usageLedger->words_debited ?? 0);
            
            // Increment chat count (one ledger entry per chat message exchange)
            $user->increment('chat_count_month', 1);
        }
    }
}

