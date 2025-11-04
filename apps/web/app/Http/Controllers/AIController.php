<?php

namespace App\Http\Controllers;

use App\LLM\ModelRouter;
use App\Models\AiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIController extends Controller
{
    public function __construct(
        protected ModelRouter $router
    ) {}

    /**
     * Test AI response with enhanced tracking
     */
    public function testResponse(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'model' => 'string|nullable',
            'chat_id' => 'integer|nullable|exists:chats,id',
        ]);

        $modelKey = $request->input('model', 'gpt-3.5-turbo');
        $message = $request->input('message');
        $chatId = $request->input('chat_id');

        try {
            // Prepare messages for AI
            $messages = [
                ['role' => 'user', 'content' => $message],
            ];

            // Get enhanced response with metadata
            $response = $this->router->getEnhancedChatResponse($modelKey, $messages, [
                'user_id' => Auth::id(),
                'chat_id' => $chatId,
                'test_request' => true,
            ]);

            // Log to database
            $aiLog = AiLog::createLog([
                'provider' => $response['provider'],
                'model_used' => $response['modelUsed'],
                'latency_ms' => $response['latencyMs'],
                'input_tokens' => $response['inputTokens'],
                'output_tokens' => $response['outputTokens'],
                'total_tokens' => $response['totalTokens'],
                'response_text' => Str::limit($response['responseText'], 200),
                'metadata' => $response['metadata'],
                'user_id' => Auth::id(),
                'chat_id' => $chatId,
            ]);

            // Log to console for debugging
            Log::info('AI Response Summary', [
                'provider' => $response['provider'],
                'model' => $response['modelUsed'],
                'latency_ms' => $response['latencyMs'],
                'tokens' => $response['totalTokens'],
                'ai_log_id' => $aiLog->id,
            ]);

            // Add AI log ID to response
            $response['ai_log_id'] = $aiLog->id;

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('AI Response Error', [
                'error' => $e->getMessage(),
                'model' => $modelKey,
                'user_id' => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'provider' => 'unknown',
                'modelUsed' => $modelKey,
                'latencyMs' => 0,
                'inputTokens' => 0,
                'outputTokens' => 0,
                'totalTokens' => 0,
                'responseText' => '',
            ], 500);
        }
    }

    /**
     * Get AI logs for debugging/monitoring
     */
    public function getLogs(Request $request)
    {
        $request->validate([
            'limit' => 'integer|min:1|max:100',
            'provider' => 'string|nullable',
            'model' => 'string|nullable',
        ]);

        $query = AiLog::query()
            ->with(['user', 'chat'])
            ->orderBy('created_at', 'desc');

        if ($request->has('provider')) {
            $query->where('provider', $request->input('provider'));
        }

        if ($request->has('model')) {
            $query->where('model_used', $request->input('model'));
        }

        $limit = $request->input('limit', 20);
        $logs = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'logs' => $logs,
            'total' => $logs->count(),
        ]);
    }

    /**
     * Get AI performance statistics
     */
    public function getStats(Request $request)
    {
        $request->validate([
            'days' => 'integer|min:1|max:365',
        ]);

        $days = $request->input('days', 7);
        $since = now()->subDays($days);

        $stats = AiLog::where('created_at', '>=', $since)
            ->selectRaw('
                provider,
                model_used,
                COUNT(*) as total_requests,
                AVG(latency_ms) as avg_latency_ms,
                SUM(input_tokens) as total_input_tokens,
                SUM(output_tokens) as total_output_tokens,
                SUM(total_tokens) as total_tokens
            ')
            ->groupBy('provider', 'model_used')
            ->orderBy('total_requests', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'period_days' => $days,
            'stats' => $stats,
        ]);
    }
}
