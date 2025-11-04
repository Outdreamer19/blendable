<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatGPTImporter
{
    /**
     * Import ChatGPT conversation from JSON
     */
    public function import(array $data, User $user, Workspace $workspace): Chat
    {
        try {
            DB::beginTransaction();

            // Create a new chat
            $chat = Chat::create([
                'workspace_id' => $workspace->id,
                'title' => $this->extractTitle($data),
                'settings' => [
                    'imported_from' => 'chatgpt',
                    'imported_at' => now(),
                ],
            ]);

            // Import messages
            $messages = $this->extractMessages($data);
            foreach ($messages as $messageData) {
                Message::create([
                    'chat_id' => $chat->id,
                    'user_id' => $messageData['role'] === 'user' ? $user->id : null,
                    'role' => $this->mapRole($messageData['role']),
                    'content' => $messageData['content'],
                    'model_key' => $this->extractModel($data),
                    'created_at' => $messageData['created_at'] ?? now(),
                ]);
            }

            // Update chat with last message timestamp
            $chat->update([
                'last_message_at' => $chat->messages()->latest()->first()?->created_at,
            ]);

            DB::commit();

            Log::info('ChatGPT conversation imported successfully', [
                'chat_id' => $chat->id,
                'user_id' => $user->id,
                'workspace_id' => $workspace->id,
                'message_count' => count($messages),
            ]);

            return $chat;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to import ChatGPT conversation', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'workspace_id' => $workspace->id,
            ]);
            throw $e;
        }
    }

    /**
     * Extract title from ChatGPT data
     */
    protected function extractTitle(array $data): string
    {
        // Try to get title from various possible locations
        if (isset($data['title'])) {
            return $data['title'];
        }

        if (isset($data['conversation_title'])) {
            return $data['conversation_title'];
        }

        if (isset($data['name'])) {
            return $data['name'];
        }

        // Generate title from first user message
        $messages = $this->extractMessages($data);
        $firstUserMessage = collect($messages)->firstWhere('role', 'user');

        if ($firstUserMessage) {
            $content = $firstUserMessage['content'];

            return strlen($content) > 50 ? substr($content, 0, 50).'...' : $content;
        }

        return 'Imported ChatGPT Conversation';
    }

    /**
     * Extract messages from ChatGPT data
     */
    protected function extractMessages(array $data): array
    {
        $messages = [];

        // Handle different ChatGPT export formats
        if (isset($data['mapping'])) {
            // New format with mapping
            foreach ($data['mapping'] as $nodeId => $node) {
                if (isset($node['message']) && isset($node['message']['content'])) {
                    $message = $node['message'];
                    $messages[] = [
                        'role' => $this->mapRole($message['author']['role']),
                        'content' => $this->extractContent($message['content']),
                        'created_at' => isset($message['create_time']) ?
                            now()->timestamp($message['create_time']) : now(),
                    ];
                }
            }
        } elseif (isset($data['messages'])) {
            // Direct messages array
            foreach ($data['messages'] as $message) {
                $messages[] = [
                    'role' => $this->mapRole($message['role']),
                    'content' => $this->extractContent($message['content']),
                    'created_at' => isset($message['created_at']) ?
                        now()->parse($message['created_at']) : now(),
                ];
            }
        } elseif (isset($data['conversation'])) {
            // Conversation format
            foreach ($data['conversation'] as $message) {
                $messages[] = [
                    'role' => $this->mapRole($message['role']),
                    'content' => $this->extractContent($message['content']),
                    'created_at' => isset($message['timestamp']) ?
                        now()->parse($message['timestamp']) : now(),
                ];
            }
        }

        // Sort messages by creation time
        usort($messages, function ($a, $b) {
            return $a['created_at'] <=> $b['created_at'];
        });

        return $messages;
    }

    /**
     * Extract content from message
     */
    protected function extractContent($content): string
    {
        if (is_string($content)) {
            return $content;
        }

        if (is_array($content)) {
            if (isset($content['parts'])) {
                // Handle parts array
                return implode(' ', array_map(function ($part) {
                    return is_string($part) ? $part : '';
                }, $content['parts']));
            }

            if (isset($content['text'])) {
                return $content['text'];
            }

            // Try to extract text from any string values
            $textParts = [];
            array_walk_recursive($content, function ($value) use (&$textParts) {
                if (is_string($value) && ! empty(trim($value))) {
                    $textParts[] = $value;
                }
            });

            return implode(' ', $textParts);
        }

        return '';
    }

    /**
     * Map ChatGPT role to our role format
     */
    protected function mapRole(string $role): string
    {
        $roleMap = [
            'user' => 'user',
            'assistant' => 'assistant',
            'system' => 'system',
            'model' => 'assistant',
            'human' => 'user',
        ];

        return $roleMap[strtolower($role)] ?? 'user';
    }

    /**
     * Extract model information
     */
    protected function extractModel(array $data): ?string
    {
        if (isset($data['model'])) {
            return $data['model'];
        }

        if (isset($data['model_name'])) {
            return $data['model_name'];
        }

        // Try to extract from messages
        $messages = $this->extractMessages($data);
        foreach ($messages as $message) {
            if (isset($message['model'])) {
                return $message['model'];
            }
        }

        return 'gpt-3.5-turbo'; // Default fallback
    }

    /**
     * Validate ChatGPT import data
     */
    public function validate(array $data): array
    {
        $errors = [];

        if (empty($data)) {
            $errors[] = 'Import data is empty';

            return $errors;
        }

        // Check for required fields or structures
        $hasMessages = isset($data['mapping']) || isset($data['messages']) || isset($data['conversation']);

        if (! $hasMessages) {
            $errors[] = 'No messages found in import data';
        }

        // Validate messages structure
        $messages = $this->extractMessages($data);
        if (empty($messages)) {
            $errors[] = 'No valid messages could be extracted';
        }

        return $errors;
    }
}
