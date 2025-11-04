<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

Route::middleware(['web'])->group(function(){
    Route::get('/app/chats/{id}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/api/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
});
