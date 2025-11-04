<?php

namespace App\Jobs;

use App\LLM\ModelRouter;
use App\Models\Chat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateChatTitleJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected Chat $chat;

    protected string $content;

    protected string $modelKey;

    /**
     * Create a new job instance.
     */
    public function __construct(Chat $chat, string $content, string $modelKey)
    {
        $this->chat = $chat;
        $this->content = $content;
        $this->modelKey = $modelKey;
    }

    /**
     * Execute the job.
     */
    public function handle(ModelRouter $router): void
    {
        try {
            // Clean and truncate the content for title generation
            $cleanContent = $this->cleanContentForTitle($this->content);

            // If content is too short or empty, use a default title
            if (strlen($cleanContent) < 10) {
                $this->chat->update(['title' => 'Quick Chat']);

                return;
            }

            // Use a simple, fast model for title generation (like GPT-3.5-turbo)
            $titleModel = 'gpt-3.5-turbo'; // Use a fast, cheap model for titles

            $prompt = "Generate a short, descriptive title (max 50 characters) for a chat conversation that starts with this message: \"{$cleanContent}\"\n\nTitle:";

            $titleResponse = $router->getChatResponse($titleModel, [
                ['role' => 'user', 'content' => $prompt],
            ]);

            if ($titleResponse && ! empty(trim($titleResponse))) {
                $title = trim($titleResponse);
                // Ensure title is not too long and clean
                $title = substr($title, 0, 50);
                $title = preg_replace('/[^\w\s\-.,!?]/', '', $title);

                if (! empty($title)) {
                    $this->chat->update(['title' => $title]);

                    return;
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail the job
            Log::warning('Failed to generate chat title', [
                'chat_id' => $this->chat->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Fallback: generate a simple title from the content
        $fallbackTitle = $this->generateFallbackTitle($this->content);
        $this->chat->update(['title' => $fallbackTitle]);
    }

    protected function cleanContentForTitle(string $content): string
    {
        // Remove extra whitespace and newlines
        $content = preg_replace('/\s+/', ' ', $content);

        // Remove common prefixes that don't add value to titles
        $content = preg_replace('/^(hi|hello|hey|can you|please|help me|i need|i want|how do|what is|explain|tell me)\s*/i', '', $content);

        // Truncate to reasonable length for title generation
        return substr(trim($content), 0, 200);
    }

    protected function generateFallbackTitle(string $content): string
    {
        // Clean the content
        $cleanContent = $this->cleanContentForTitle($content);

        // Extract first few words
        $words = explode(' ', $cleanContent);
        $titleWords = array_slice($words, 0, 4);
        $title = implode(' ', $titleWords);

        // Ensure it's not too long
        if (strlen($title) > 40) {
            $title = substr($title, 0, 37).'...';
        }

        // If still empty or too short, use a generic title
        if (strlen($title) < 3) {
            $title = 'New Chat';
        }

        return $title;
    }
}
