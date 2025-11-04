<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\LLM\ModelRouter;

class ChatController extends Controller
{
    public function show(int $id)
    {
        // return Inertia page (stub)
        return inertia('Chat/Show', ['chatId' => $id]);
    }

    public function sendMessage(Request $request, ModelRouter $router): StreamedResponse
    {
        $messages = $request->input('messages', []);
        $modelKey = $request->input('model', 'gpt-4o');

        return response()->stream(function () use ($router, $messages, $modelKey) {
            $client = $router->clientFor($modelKey);
            foreach ($client->stream($messages) as $chunk) {
                echo "data: " . json_encode(['delta' => $chunk]) . "\n\n";
                @ob_flush(); @flush();
            }
            echo "data: [DONE]\n\n";
        }, 200, ['Content-Type' => 'text/event-stream']);
    }
}
