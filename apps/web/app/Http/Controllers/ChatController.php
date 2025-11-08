<?php

namespace App\Http\Controllers;

use App\LLM\ModelRouter;
use App\Models\AiLog;
use App\Models\Chat;
use App\Models\Message;
use App\Models\ModelConfig;
use App\Models\UsageLedger;
use App\Tools\ToolManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ChatController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected ModelRouter $router,
        protected ToolManager $toolManager
    ) {}

    public function index()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        // Initialize empty collections if no workspace
        $chats = collect();
        $personas = collect();

        if ($currentWorkspace) {
            // Get all chats with last message
            $chats = Chat::where('workspace_id', $currentWorkspace->id)
                ->with(['persona', 'messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->orderBy('last_message_at', 'desc')
                ->get()
                ->map(function ($chat) {
                    return [
                        'id' => (string) $chat->id,
                        'title' => $chat->title,
                        'lastMessage' => $chat->messages->first()?->content,
                        'updatedAt' => $chat->last_message_at?->toISOString() ?? $chat->created_at->toISOString(),
                        'pinned' => $chat->pinned ?? false,
                        'unread' => false, // You can implement unread logic here
                    ];
                });

            // Get available personas
            $personas = \App\Models\Persona::where('workspace_id', $currentWorkspace->id)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }

        // Get available models
        $availableModels = $this->router->getAvailableModels();

        return inertia('Chats', [
            'user' => $user,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'currentWorkspace' => $currentWorkspace,
            'chats' => $chats,
            'availableModels' => $this->router->getAvailableModels(),
            'can' => [
                'create' => true,
                'edit' => true,
                'delete' => true,
            ],
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        if (! $currentWorkspace) {
            return redirect()->route('chats.index')->with('error', 'No workspace available. Please create or join a workspace first.');
        }

        // Get available personas
        $personas = \App\Models\Persona::where('workspace_id', $currentWorkspace->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get available models
        $availableModels = $this->router->getAvailableModels();

        // Check if a specific persona is requested
        $selectedPersona = null;
        if ($request->has('persona')) {
            $selectedPersona = \App\Models\Persona::find($request->input('persona'));
        }

        return inertia('Chats', [
            'user' => $user,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'currentWorkspace' => $currentWorkspace,
            'chats' => collect(),
            'personas' => $personas,
            'availableModels' => $availableModels,
            'selectedPersona' => $selectedPersona,
            'can' => [
                'create' => true,
                'edit' => true,
                'delete' => true,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        if (! $currentWorkspace) {
            // Try to set first available workspace as current
            $firstWorkspace = $user->workspaces()->first();
            if ($firstWorkspace) {
                $user->setCurrentWorkspace($firstWorkspace);
                $currentWorkspace = $firstWorkspace;
            } else {
                return redirect()->route('chats.index')->with('error', 'No workspace available. Please create or join a workspace first.');
            }
        }

        $workspaceId = $request->input('workspace_id', $currentWorkspace->id);
        $personaId = $request->input('persona_id');

        // Ensure workspace_id belongs to user
        $workspace = $user->workspaces()->find($workspaceId);
        if (! $workspace) {
            $workspace = $currentWorkspace;
            $workspaceId = $currentWorkspace->id;
        }

        $chat = new Chat([
            'user_id' => $user->id,
            'workspace_id' => $workspaceId,
            'persona_id' => $personaId,
            'title' => $request->input('title', 'New Chat'),
            'last_message_at' => now(),
        ]);
        $chat->save();

        return redirect()->route('chats.show', $chat->id);
    }

    public function startWithPersona(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        if (! $currentWorkspace) {
            return redirect()->route('chats.index')->with('error', 'No workspace available. Please create or join a workspace first.');
        }

        $personaId = (int) $request->query('persona');
        if (! $personaId) {
            return redirect()->route('chats.index')->with('error', 'Missing persona.');
        }

        $chat = new Chat([
            'user_id' => $user->id,
            'workspace_id' => $currentWorkspace->id,
            'persona_id' => $personaId,
            'title' => 'New Chat',
            'last_message_at' => now(),
        ]);
        $chat->save();

        return redirect()->route('chats.show', $chat->id);
    }

    public function show(Chat $chat)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        // Ensure relationships are loaded
        $chat->load(['messages', 'persona', 'workspace']);

        $this->authorize('view', $chat);

        // Get all chats for sidebar
        $chats = collect();
        if ($currentWorkspace) {
            $chats = Chat::where('workspace_id', $currentWorkspace->id)
                ->with(['persona', 'messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->orderBy('last_message_at', 'desc')
                ->get()
                ->map(function ($chatItem) {
                    return [
                        'id' => (string) $chatItem->id,
                        'title' => $chatItem->title,
                        'lastMessage' => $chatItem->messages->first()?->content,
                        'updatedAt' => $chatItem->last_message_at?->toISOString() ?? $chatItem->created_at->toISOString(),
                        'pinned' => $chatItem->pinned ?? false,
                        'unread' => false,
                    ];
                });
        }

        // Format active chat
        $activeChat = [
            'id' => (string) $chat->id,
            'title' => $chat->title,
            'model' => $chat->model_key,
            'persona' => $chat->persona?->name,
            'messages' => $chat->messages->map(function ($message) {
                return [
                    'id' => (string) $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'model_key' => $message->model_key,
                    'tokens_in' => $message->tokens_in,
                    'tokens_out' => $message->tokens_out,
                    'createdAt' => $message->created_at->toISOString(),
                ];
            })->toArray(),
        ];

        return inertia('Chats', [
            'user' => $user,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'currentWorkspace' => $currentWorkspace,
            'chats' => $chats,
            'activeChat' => $activeChat,
            'availableModels' => $this->router->getAvailableModels(),
            'can' => [
                'create' => true,
                'edit' => $user->can('update', $chat),
                'delete' => $user->can('delete', $chat),
            ],
        ]);
    }

    public function sendMessage(Request $request, Chat $chat): StreamedResponse
    {
        $this->authorize('update', $chat);

        $request->validate([
            'content' => 'required|string|max:10000',
            'model' => 'string|nullable',
            'persona_id' => 'integer|nullable|exists:personas,id',
        ]);

        $modelKey = $request->input('model', 'auto');
        $content = $request->input('content');
        $personaId = $request->input('persona_id');

        // Update chat's model and persona if provided
        if ($modelKey !== $chat->model_key || $personaId !== $chat->persona_id) {
            $chat->update([
                'model_key' => $modelKey,
                'persona_id' => $personaId,
            ]);
        }

        // Create user message
        $userMessage = $chat->messages()->create([
            'user_id' => Auth::id(),
            'role' => 'user',
            'content' => $content,
            'model_key' => $modelKey,
        ]);

        // Check if we need to generate a title (first user message and default title)
        $shouldGenerateTitle = $chat->messages()->where('role', 'user')->count() === 1 && ($chat->title === 'New Chat' || empty($chat->title));

        // Get conversation history
        $messages = $this->buildMessageHistory($chat, $modelKey);

        $response = new StreamedResponse(function () use ($chat, $modelKey, $messages, $userMessage, $shouldGenerateTitle, $content) {
            $fullResponseContent = '';
            $selectedModelConfig = \App\Models\ModelConfig::where('model_key', $modelKey)->first();

            try {
                $this->router->streamChatResponse(
                    $modelKey,
                    $messages,
                    function ($delta, $finishReason) use (&$fullResponseContent) {
                        $fullResponseContent .= $delta;
                        echo 'data: '.json_encode(['delta' => $delta])."\n\n";
                        ob_flush();
                        flush();
                    }
                );

                // Create assistant message
                $chat->messages()->create([
                    'user_id' => null, // Assistant message
                    'role' => 'assistant',
                    'content' => $fullResponseContent,
                    'model_key' => $modelKey,
                ]);

                // Update chat's last message timestamp
                $chat->update(['last_message_at' => now()]);

                // Log usage
                \App\Models\UsageLedger::create([
                    'user_id' => Auth::id(),
                    'workspace_id' => $chat->workspace_id,
                    'model_key' => $modelKey,
                    'tokens_in' => $selectedModelConfig ? $selectedModelConfig->calculateTokenCost($userMessage->content) : 0,
                    'tokens_out' => $selectedModelConfig ? $selectedModelConfig->calculateTokenCost($fullResponseContent) : 0,
                    'words_debited' => str_word_count($userMessage->content) + str_word_count($fullResponseContent),
                    'usage_date' => now(),
                ]);

                // Generate chat title synchronously if needed (for better UX)
                if ($shouldGenerateTitle) {
                    try {
                        // Generate title immediately using the job's logic
                        $cleanContent = $this->cleanContentForTitle($content);

                        if (strlen($cleanContent) >= 10) {
                            // Use fallback title generation for speed (no API call needed)
                            $titleWords = array_slice(explode(' ', $cleanContent), 0, 4);
                            $title = implode(' ', $titleWords);
                            $title = substr($title, 0, 40);
                            $title = preg_replace('/[^\w\s\-.,!?]/', '', $title);

                            if (strlen($title) >= 3) {
                                $chat->update(['title' => $title]);
                            } else {
                                // Fallback to first few words from content
                                $chat->update(['title' => substr($cleanContent, 0, 30) ?: 'Quick Chat']);
                            }
                        } else {
                            $chat->update(['title' => 'Quick Chat']);
                        }
                    } catch (\Exception $e) {
                        // If title generation fails, use fallback
                        \Illuminate\Support\Facades\Log::warning('Failed to generate chat title', [
                            'chat_id' => $chat->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Still try fallback
                        $cleanContent = preg_replace('/\s+/', ' ', trim($content));
                        $title = substr($cleanContent, 0, 30) ?: 'New Chat';
                        $chat->update(['title' => $title]);
                    }
                }

            } catch (\Exception $e) {
                echo 'data: '.json_encode(['error' => $e->getMessage()])."\n\n";
                ob_flush();
                flush();
            } finally {
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Disable buffering for Nginx
        $response->headers->set('X-Accel-Timeout', '600'); // 10 minutes for Nginx
        $response->headers->set('Keep-Alive', 'timeout=600, max=1000');
        // Set a longer timeout for streaming responses
        set_time_limit(600); // 10 minutes

        return $response;
    }

    /**
     * Send message with enhanced AI tracking
     */
    public function sendMessageWithTracking(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'content' => 'required|string|max:10000',
            'model' => 'string|nullable',
            'persona_id' => 'integer|nullable|exists:personas,id',
        ]);

        $modelKey = $request->input('model', 'auto');
        $content = $request->input('content');
        $personaId = $request->input('persona_id');

        // Update chat's model and persona if provided
        if ($modelKey !== $chat->model_key || $personaId !== $chat->persona_id) {
            $chat->update([
                'model_key' => $modelKey,
                'persona_id' => $personaId,
            ]);
        }

        // Create user message
        $userMessage = $chat->messages()->create([
            'user_id' => Auth::id(),
            'role' => 'user',
            'content' => $content,
            'model_key' => $modelKey,
        ]);

        // Get conversation history
        $messages = $this->buildMessageHistory($chat, $modelKey);

        try {
            // Get enhanced response with metadata
            $response = $this->router->getEnhancedChatResponse($modelKey, $messages, [
                'user_id' => Auth::id(),
                'chat_id' => $chat->id,
                'message_id' => $userMessage->id,
            ]);

            // Create assistant message
            $assistantMessage = $chat->messages()->create([
                'user_id' => null, // Assistant message
                'role' => 'assistant',
                'content' => $response['responseText'],
                'model_key' => $modelKey,
                'tokens_in' => $response['inputTokens'],
                'tokens_out' => $response['outputTokens'],
            ]);

            // Update chat's last message timestamp
            $chat->update(['last_message_at' => now()]);

            // Log to AI logs table
            $aiLog = AiLog::createLog([
                'provider' => $response['provider'],
                'model_used' => $response['modelUsed'],
                'latency_ms' => $response['latencyMs'],
                'input_tokens' => $response['inputTokens'],
                'output_tokens' => $response['outputTokens'],
                'total_tokens' => $response['totalTokens'],
                'response_text' => \Illuminate\Support\Str::limit($response['responseText'], 200),
                'metadata' => $response['metadata'],
                'user_id' => Auth::id(),
                'chat_id' => $chat->id,
                'message_id' => $assistantMessage->id,
            ]);

            // Log to AI-specific log channel
            \Illuminate\Support\Facades\Log::channel('ai_logs')->info('AI Response Summary', [
                'provider' => $response['provider'],
                'model' => $response['modelUsed'],
                'latency_ms' => $response['latencyMs'],
                'tokens' => $response['totalTokens'],
                'ai_log_id' => $aiLog->id,
                'chat_id' => $chat->id,
            ]);

            // Log usage for billing
            $selectedModelConfig = \App\Models\ModelConfig::where('model_key', $modelKey)->first();
            \App\Models\UsageLedger::create([
                'user_id' => Auth::id(),
                'workspace_id' => $chat->workspace_id,
                'model_key' => $modelKey,
                'tokens_in' => $response['inputTokens'],
                'tokens_out' => $response['outputTokens'],
                'words_debited' => str_word_count($content) + str_word_count($response['responseText']),
                'usage_date' => now(),
            ]);

            // Generate chat title synchronously if needed (for better UX)
            $shouldGenerateTitle = $chat->messages()->where('role', 'user')->count() === 1 && ($chat->title === 'New Chat' || empty($chat->title));
            if ($shouldGenerateTitle) {
                try {
                    // Generate title immediately using fallback logic for speed
                    $cleanContent = $this->cleanContentForTitle($content);

                    if (strlen($cleanContent) >= 10) {
                        $titleWords = array_slice(explode(' ', $cleanContent), 0, 4);
                        $title = implode(' ', $titleWords);
                        $title = substr($title, 0, 40);
                        $title = preg_replace('/[^\w\s\-.,!?]/', '', $title);

                        if (strlen($title) >= 3) {
                            $chat->update(['title' => $title]);
                        } else {
                            $chat->update(['title' => substr($cleanContent, 0, 30) ?: 'Quick Chat']);
                        }
                    } else {
                        $chat->update(['title' => 'Quick Chat']);
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('Failed to generate chat title', [
                        'chat_id' => $chat->id,
                        'error' => $e->getMessage(),
                    ]);
                    $cleanContent = preg_replace('/\s+/', ' ', trim($content));
                    $title = substr($cleanContent, 0, 30) ?: 'New Chat';
                    $chat->update(['title' => $title]);
                }
            }

            // Return enhanced response
            return response()->json([
                'success' => true,
                'message' => $assistantMessage,
                'ai_response' => $response,
                'ai_log_id' => $aiLog->id,
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Response Error', [
                'error' => $e->getMessage(),
                'model' => $modelKey,
                'user_id' => Auth::id(),
                'chat_id' => $chat->id,
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function switchModel(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'model' => 'required|string',
        ]);

        $modelKey = $request->input('model');

        // Update chat settings to remember the model choice
        $settings = $chat->settings ?? [];
        $settings['model'] = $modelKey;
        $chat->update(['settings' => $settings]);

        return redirect()->back();
    }

    public function switchPersona(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'persona_id' => 'nullable|integer|exists:personas,id',
        ]);

        $personaId = $request->input('persona_id');
        $chat->update(['persona_id' => $personaId]);

        return response()->json(['success' => true]);
    }

    public function share(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $chat->share_token = \Illuminate\Support\Str::random(32);
        $chat->save();

        return response()->json([
            'share_token' => $chat->share_token,
            'share_url' => route('chat.shared', $chat->share_token),
        ]);
    }

    public function unshare(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $chat->share_token = null;
        $chat->save();

        return response()->json(['message' => 'Chat unshared successfully.']);
    }

    public function showShared(string $shareToken)
    {
        $chat = Chat::with(['messages', 'persona', 'workspace'])
            ->where('share_token', $shareToken)
            ->firstOrFail();

        return inertia('Chat/ShowShared', [
            'chat' => $chat,
        ]);
    }

    public function destroy(Chat $chat)
    {
        $this->authorize('delete', $chat);
        $chat->delete();

        return redirect()->route('chats.index')->with('success', 'Chat deleted successfully.');
    }

    public function pin(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $chat->update(['pinned' => ! $chat->pinned]);

        return response()->json(['success' => true, 'pinned' => $chat->pinned]);
    }

    public function rename(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $chat->update(['title' => $request->input('title')]);

        return response()->json(['success' => true, 'title' => $chat->title]);
    }

    public function export(Request $request, Chat $chat)
    {
        $this->authorize('view', $chat);

        $request->validate([
            'format' => 'required|in:markdown,pdf,docx',
        ]);

        $format = $request->input('format');

        // For now, just return a simple markdown export
        $content = "# {$chat->title}\n\n";
        foreach ($chat->messages as $message) {
            $role = $message->role === 'user' ? 'You' : 'Assistant';
            $content .= "## {$role}\n\n{$message->content}\n\n";
        }

        return response($content)
            ->header('Content-Type', 'text/markdown')
            ->header('Content-Disposition', "attachment; filename=\"{$chat->title}.md\"");
    }

    public function shareLink(Chat $chat)
    {
        $this->authorize('update', $chat);

        if (! $chat->share_token) {
            $chat->update([
                'share_token' => $chat->generateShareToken(),
                'is_shared_view_only' => true,
            ]);
        }

        return response()->json([
            'share_url' => route('chat.shared', $chat->share_token),
        ]);
    }

    public function shared(string $token)
    {
        $chat = Chat::with(['messages', 'persona', 'workspace'])
            ->where('share_token', $token)
            ->where('is_shared_view_only', true)
            ->firstOrFail();

        return inertia('Chat/Shared', [
            'chat' => $chat,
        ]);
    }

    protected function buildMessageHistory(Chat $chat, string $modelKey): array
    {
        $messages = [];

        // Add system message if persona is set
        if ($chat->persona && $chat->persona->system_prompt) {
            $messages[] = [
                'role' => 'system',
                'content' => $chat->persona->system_prompt,
            ];
        }

        // Add conversation history
        foreach ($chat->messages()->orderBy('created_at')->get() as $message) {
            $messages[] = [
                'role' => $message->role,
                'content' => $message->content,
            ];
        }

        return $messages;
    }

    public function executeTool(Request $request, Chat $chat)
    {
        $this->authorize('update', $chat);

        $request->validate([
            'tool_name' => 'required|string',
            'parameters' => 'required|array',
        ]);

        $toolName = $request->input('tool_name');
        $parameters = $request->input('parameters');

        // Add workspace context to parameters
        $parameters['workspace_id'] = $chat->workspace_id;

        $result = $this->toolManager->executeTool($toolName, $parameters);

        // Create a tool message in the chat
        $message = $chat->messages()->create([
            'role' => 'tool',
            'content' => json_encode($result),
            'tool_calls_json' => [
                'tool_name' => $toolName,
                'parameters' => $parameters,
            ],
        ]);

        return response()->json([
            'success' => ! isset($result['error']),
            'result' => $result,
            'message_id' => $message->id,
        ]);
    }

    protected function recordUsage(Chat $chat, string $modelKey, int $tokensIn, int $tokensOut, int $wordsOut): void
    {
        $modelConfig = ModelConfig::where('model_key', $modelKey)->first();
        $multiplier = $modelConfig ? $modelConfig->multiplier : 1.0;

        UsageLedger::create([
            'user_id' => Auth::id(),
            'team_id' => $chat->workspace->team_id,
            'workspace_id' => $chat->workspace_id,
            'model_key' => $modelKey,
            'tokens_in' => $tokensIn,
            'tokens_out' => $tokensOut,
            'words_debited' => $wordsOut * $multiplier,
            'multiplier' => $multiplier,
            'usage_date' => now()->toDateString(),
        ]);
    }

    /**
     * Clean content for title generation
     */
    protected function cleanContentForTitle(string $content): string
    {
        // Remove extra whitespace and newlines
        $content = preg_replace('/\s+/', ' ', $content);

        // Remove common prefixes that don't add value to titles
        $content = preg_replace('/^(hi|hello|hey|can you|please|help me|i need|i want|how do|what is|explain|tell me)\s*/i', '', $content);

        // Truncate to reasonable length for title generation
        return substr(trim($content), 0, 200);
    }
}
