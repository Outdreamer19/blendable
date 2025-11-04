<?php

namespace App\Http\Controllers;

use App\Models\ModelConfig;
use App\Models\UsageLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UsageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        // Calculate cost estimates (assuming $0.01 per 1000 words)
        $costPerThousandWords = 0.01;

        // Get usage data
        $query = UsageLedger::where('user_id', $user->id);

        if ($currentWorkspace) {
            $query->where('workspace_id', $currentWorkspace->id);
        }

        $usageData = $query
            ->where('usage_date', '>=', $startDate)
            ->selectRaw('
                usage_date,
                model_key,
                SUM(tokens_in) as total_tokens_in,
                SUM(tokens_out) as total_tokens_out,
                SUM(words_debited) as total_words_debited,
                COUNT(*) as request_count
            ')
            ->groupBy('usage_date', 'model_key')
            ->orderBy('usage_date', 'desc')
            ->get();

        // Get model configurations
        $modelConfigs = ModelConfig::where('enabled', true)->get();

        // Calculate totals
        $totals = [
            'total_tokens_in' => $usageData->sum('total_tokens_in'),
            'total_tokens_out' => $usageData->sum('total_tokens_out'),
            'total_words_debited' => $usageData->sum('total_words_debited'),
            'total_requests' => $usageData->sum('request_count'),
        ];

        // Get daily usage for chart
        $dailyQuery = UsageLedger::where('user_id', $user->id);

        if ($currentWorkspace) {
            $dailyQuery->where('workspace_id', $currentWorkspace->id);
        }

        $dailyUsage = $dailyQuery
            ->where('usage_date', '>=', $startDate)
            ->selectRaw('
                usage_date as date,
                SUM(words_debited) as words,
                SUM(tokens_in) as daily_tokens_in,
                SUM(tokens_out) as daily_tokens_out,
                COUNT(*) as daily_requests
            ')
            ->groupBy('usage_date')
            ->orderBy('usage_date')
            ->get()
            ->map(function ($item) use ($costPerThousandWords) {
                $item->cost = ($item->words / 1000) * $costPerThousandWords;

                return $item;
            });

        // Get model usage breakdown
        $modelQuery = UsageLedger::where('user_id', $user->id);

        if ($currentWorkspace) {
            $modelQuery->where('workspace_id', $currentWorkspace->id);
        }

        $modelUsage = $modelQuery
            ->where('usage_date', '>=', $startDate)
            ->selectRaw('
                model_key,
                SUM(tokens_in) as total_tokens_in,
                SUM(tokens_out) as total_tokens_out,
                SUM(words_debited) as total_words_debited,
                COUNT(*) as request_count,
                AVG(multiplier) as avg_multiplier
            ')
            ->groupBy('model_key')
            ->orderBy('total_words_debited', 'desc')
            ->get()
            ->map(function ($item) use ($modelConfigs, $costPerThousandWords) {
                $modelConfig = $modelConfigs->firstWhere('model_key', $item->model_key);

                return (object) [
                    'model_key' => $item->model_key,
                    'display_name' => $modelConfig ? $modelConfig->display_name : $item->model_key,
                    'provider' => $modelConfig ? $modelConfig->provider : 'unknown',
                    'words' => $item->total_words_debited,
                    'cost' => ($item->total_words_debited / 1000) * $costPerThousandWords,
                ];
            });

        // Get hourly usage pattern
        $hourlyQuery = UsageLedger::where('user_id', $user->id);

        if ($currentWorkspace) {
            $hourlyQuery->where('workspace_id', $currentWorkspace->id);
        }

        $hourlyUsage = $hourlyQuery
            ->where('usage_date', '>=', $startDate)
            ->selectRaw('
                '.(config('database.default') === 'sqlite' ? 'strftime("%H", created_at)' : 'EXTRACT(hour FROM created_at)').' as hour,
                COUNT(*) as request_count,
                SUM(words_debited) as total_words
            ')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Get workspace usage comparison
        $workspaceUsage = UsageLedger::where('user_id', $user->id)
            ->where('usage_date', '>=', $startDate)
            ->selectRaw('
                workspace_id,
                SUM(words_debited) as total_words,
                COUNT(*) as request_count
            ')
            ->groupBy('workspace_id')
            ->with('workspace')
            ->get()
            ->map(function ($item) use ($costPerThousandWords) {
                return (object) [
                    'id' => $item->workspace_id,
                    'name' => $item->workspace ? $item->workspace->name : 'Unknown',
                    'team_name' => $item->workspace && $item->workspace->team ? $item->workspace->team->name : null,
                    'words' => $item->total_words,
                    'cost' => ($item->total_words / 1000) * $costPerThousandWords,
                ];
            });

        // Calculate cost estimates
        $estimatedCost = ($totals['total_words_debited'] / 1000) * $costPerThousandWords;
        $totalCost = $estimatedCost;
        $periodDays = (int) $period;
        $avgCostPerDay = $periodDays > 0 ? $totalCost / $periodDays : 0;

        return Inertia::render('Usage/Index', [
            'usageStats' => [
                'total_words' => $totals['total_words_debited'],
                'total_cost' => round($totalCost, 2),
                'total_calls' => $totals['total_requests'],
                'avg_cost_per_day' => round($avgCostPerDay, 2),
                'estimated_cost' => round($estimatedCost, 2),
                'avg_words_per_request' => $totals['total_requests'] > 0 ? round($totals['total_words_debited'] / $totals['total_requests'], 2) : 0,
            ],
            'modelUsage' => $modelUsage,
            'dailyUsage' => $dailyUsage,
            'hourlyUsage' => $hourlyUsage,
            'workspaceUsage' => $workspaceUsage,
            'period' => $period,
            'user' => $user,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'workspace' => $currentWorkspace,
        ]);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $period = $request->get('period', '30');
        $startDate = Carbon::now()->subDays($period);

        $usageData = UsageLedger::where('user_id', $user->id)
            ->where('workspace_id', $currentWorkspace->id)
            ->where('usage_date', '>=', $startDate)
            ->with('modelConfig')
            ->orderBy('usage_date', 'desc')
            ->get();

        $csvData = [];
        $csvData[] = ['Date', 'Model', 'Tokens In', 'Tokens Out', 'Words Debited', 'Multiplier'];

        foreach ($usageData as $usage) {
            $csvData[] = [
                $usage->usage_date,
                $usage->model_key,
                $usage->tokens_in,
                $usage->tokens_out,
                $usage->words_debited,
                $usage->multiplier,
            ];
        }

        $filename = 'usage_export_'.now()->format('Y-m-d').'.csv';

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        $format = $request->get('format', 'csv');

        if ($format === 'json') {
            return response()->json($usageData->map(function ($usage) {
                return [
                    'date' => $usage->usage_date,
                    'model' => $usage->model_key,
                    'tokens_in' => $usage->tokens_in,
                    'tokens_out' => $usage->tokens_out,
                    'words_debited' => $usage->words_debited,
                    'multiplier' => $usage->multiplier,
                ];
            }), 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="'.str_replace('.csv', '.json', $filename).'"',
            ]);
        }

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
