<?php

namespace App\Http\Controllers;

use App\Services\ChatGPTImporter;
use App\Services\ClaudeImporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    protected ChatGPTImporter $chatGPTImporter;

    protected ClaudeImporter $claudeImporter;

    public function __construct(ChatGPTImporter $chatGPTImporter, ClaudeImporter $claudeImporter)
    {
        $this->chatGPTImporter = $chatGPTImporter;
        $this->claudeImporter = $claudeImporter;
    }

    /**
     * Show the import page
     */
    public function index()
    {
        $user = Auth::user();
        $workspaces = $user->workspaces()->get();

        return inertia('Import/Index', [
            'workspaces' => $workspaces,
        ]);
    }

    /**
     * Import ChatGPT conversation
     */
    public function importChatGPT(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json|max:10240', // 10MB max
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $workspace = $user->workspaces()->findOrFail($request->workspace_id);

        try {
            // Read and parse JSON file
            $jsonContent = file_get_contents($request->file('file')->getPathname());
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['file' => 'Invalid JSON file'])->withInput();
            }

            // Validate import data
            $errors = $this->chatGPTImporter->validate($data);
            if (! empty($errors)) {
                return back()->withErrors(['file' => implode(', ', $errors)])->withInput();
            }

            // Import the conversation
            $chat = $this->chatGPTImporter->import($data, $user, $workspace);

            return redirect()->route('chats.show', $chat)
                ->with('success', 'ChatGPT conversation imported successfully!');

        } catch (\Exception $e) {
            Log::error('ChatGPT import failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'workspace_id' => $request->workspace_id,
            ]);

            return back()->withErrors(['file' => 'Failed to import conversation: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Import Claude conversation
     */
    public function importClaude(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json|max:10240', // 10MB max
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        $workspace = $user->workspaces()->findOrFail($request->workspace_id);

        try {
            // Read and parse JSON file
            $jsonContent = file_get_contents($request->file('file')->getPathname());
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withErrors(['file' => 'Invalid JSON file'])->withInput();
            }

            // Validate import data
            $errors = $this->claudeImporter->validate($data);
            if (! empty($errors)) {
                return back()->withErrors(['file' => implode(', ', $errors)])->withInput();
            }

            // Import the conversation
            $chat = $this->claudeImporter->import($data, $user, $workspace);

            return redirect()->route('chats.show', $chat)
                ->with('success', 'Claude conversation imported successfully!');

        } catch (\Exception $e) {
            Log::error('Claude import failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'workspace_id' => $request->workspace_id,
            ]);

            return back()->withErrors(['file' => 'Failed to import conversation: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Import from JSON data (for API or direct JSON input)
     */
    public function importFromJson(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'type' => 'required|in:chatgpt,claude',
            'workspace_id' => 'required|exists:workspaces,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $workspace = $user->workspaces()->findOrFail($request->workspace_id);

        try {
            $data = $request->input('data');
            $type = $request->input('type');

            if ($type === 'chatgpt') {
                $errors = $this->chatGPTImporter->validate($data);
                if (! empty($errors)) {
                    return response()->json(['errors' => ['data' => implode(', ', $errors)]], 422);
                }
                $chat = $this->chatGPTImporter->import($data, $user, $workspace);
            } else {
                $errors = $this->claudeImporter->validate($data);
                if (! empty($errors)) {
                    return response()->json(['errors' => ['data' => implode(', ', $errors)]], 422);
                }
                $chat = $this->claudeImporter->import($data, $user, $workspace);
            }

            return response()->json([
                'success' => true,
                'chat' => $chat,
                'message' => ucfirst($type).' conversation imported successfully!',
            ]);

        } catch (\Exception $e) {
            Log::error('JSON import failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'workspace_id' => $request->workspace_id,
                'type' => $request->input('type'),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to import conversation: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get import templates/examples
     */
    public function templates()
    {
        return response()->json([
            'chatgpt' => [
                'description' => 'ChatGPT export format',
                'example' => [
                    'title' => 'Sample Conversation',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => 'Hello, how are you?',
                            'created_at' => '2024-01-01T12:00:00Z',
                        ],
                        [
                            'role' => 'assistant',
                            'content' => 'I\'m doing well, thank you! How can I help you today?',
                            'created_at' => '2024-01-01T12:00:30Z',
                        ],
                    ],
                ],
            ],
            'claude' => [
                'description' => 'Claude export format',
                'example' => [
                    'title' => 'Sample Conversation',
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => 'Hello, how are you?',
                            'created_at' => '2024-01-01T12:00:00Z',
                        ],
                        [
                            'role' => 'assistant',
                            'content' => 'I\'m doing well, thank you! How can I help you today?',
                            'created_at' => '2024-01-01T12:00:30Z',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
