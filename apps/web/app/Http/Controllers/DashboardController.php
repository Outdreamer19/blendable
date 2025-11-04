<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Persona;
use App\Models\UsageLedger;
use App\Models\Workspace;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        // Get recent chats
        $recentChats = collect();
        if ($currentWorkspace) {
            $recentChats = Chat::where('workspace_id', $currentWorkspace->id)
                ->with(['persona', 'messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->orderBy('last_message_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Get available personas
        $personas = collect();
        if ($currentWorkspace) {
            $personas = Persona::where('workspace_id', $currentWorkspace->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        // Get usage statistics for the current month
        $currentMonth = Carbon::now()->startOfMonth();
        $usageStats = UsageLedger::where('user_id', $user->id)
            ->where('usage_date', '>=', $currentMonth)
            ->selectRaw('
                SUM(tokens_in) as total_tokens_in,
                SUM(tokens_out) as total_tokens_out,
                SUM(words_debited) as total_words_debited,
                COUNT(DISTINCT usage_date) as active_days
            ')
            ->first();

        // Get workspace statistics
        $workspaceStats = [
            'total_chats' => $currentWorkspace ? Chat::where('workspace_id', $currentWorkspace->id)->count() : 0,
            'total_personas' => $currentWorkspace ? Persona::where('workspace_id', $currentWorkspace->id)->count() : 0,
            'total_messages' => $currentWorkspace ? Chat::where('workspace_id', $currentWorkspace->id)
                ->withCount('messages')
                ->get()
                ->sum('messages_count') : 0,
        ];

        // Get user's workspaces
        $workspaces = $user->workspaces()->with('team')->get();

        return Inertia::render('Dashboard', [
            'user' => $user,
            'currentWorkspace' => $currentWorkspace,
            'workspaces' => $workspaces,
            'recentChats' => $recentChats,
            'personas' => $personas,
            'usageStats' => $usageStats,
            'workspaceStats' => $workspaceStats,
        ]);
    }
}
