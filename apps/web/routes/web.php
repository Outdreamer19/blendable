<?php

use App\Http\Controllers\AIController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromptController;
use App\Http\Controllers\PromptFolderController;
use App\Http\Controllers\PublicPersonaController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Marketing routes
Route::get('/', function () {
    return Inertia::render('Marketing/Homepage');
})->name('home');

Route::get('/pricing', function () {
    return Inertia::render('Marketing/Pricing');
})->name('pricing');

Route::get('/blog', function () {
    return Inertia::render('Marketing/BlogIndex', [
        'posts' => [], // TODO: Fetch from database
    ]);
})->name('blog.index');

Route::get('/blog/{slug}', function ($slug) {
    return Inertia::render('Marketing/BlogShow', [
        'slug' => $slug,
        'post' => null, // TODO: Fetch from database
    ]);
})->name('blog.show');

Route::get('/privacy', function () {
    return Inertia::render('Marketing/Privacy');
})->name('privacy');

Route::get('/terms', function () {
    return Inertia::render('Marketing/Terms');
})->name('terms');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::get('/about', function () {
    return Inertia::render('Marketing/About');
})->name('about');

// Public personas
Route::get('/experts', [PublicPersonaController::class, 'index'])->name('public.personas.index');
Route::get('/experts/{persona}', [PublicPersonaController::class, 'show'])->name('public.personas.show');

Route::get('/reviews', function () {
    return Inertia::render('Reviews');
})->name('reviews');

Route::get('/enterprise', function () {
    return Inertia::render('Enterprise');
})->name('enterprise');

Route::get('/blog', function () {
    return Inertia::render('Blog');
})->name('blog');

Route::get('/product-updates', function () {
    return Inertia::render('ProductUpdates');
})->name('product-updates');

Route::get('/chatgpt-vs-omni', function () {
    return Inertia::render('ChatGPTVsOmni');
})->name('chatgpt-vs-omni');

Route::get('/chatgpt-down', function () {
    return Inertia::render('ChatGPTDown');
})->name('chatgpt-down');

// Shared Chat Route (publicly accessible if shared)
Route::get('/shared/{chat:share_token}', [ChatController::class, 'showShared'])->name('chat.shared');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Teams
    Route::resource('teams', TeamController::class);
    Route::post('/teams/{team}/invite', [TeamController::class, 'invite'])->name('teams.invite');
    Route::delete('/teams/{team}/members/{user}', [TeamController::class, 'removeMember'])->name('teams.remove-member');

    // Workspaces
    Route::resource('workspaces', WorkspaceController::class);
    Route::post('/workspaces/{workspace}/invite', [WorkspaceController::class, 'invite'])->name('workspaces.invite');
    Route::delete('/workspaces/{workspace}/members/{user}', [WorkspaceController::class, 'removeMember'])->name('workspaces.remove-member');

    // Chats
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    // Start a chat with a specific persona (creates and redirects to the chat)
    Route::get('/chats/start', [ChatController::class, 'startWithPersona'])->name('chats.start');
    Route::resource('chats', ChatController::class)
        ->except(['index', 'edit', 'update'])
        ->whereNumber('chat');

    // Rate limited chat endpoints
    Route::middleware(['throttle:60,1', 'enforce.plan.limits'])->group(function () {
        Route::post('/chats/{chat}/send-message', [ChatController::class, 'sendMessage'])->name('chats.send-message');
        Route::post('/chats/{chat}/send-message-enhanced', [ChatController::class, 'sendMessageWithTracking'])->name('chats.send-message-enhanced');
    });

    Route::post('/chats/{chat}/share', [ChatController::class, 'share'])->name('chats.share');
    Route::delete('/chats/{chat}/unshare', [ChatController::class, 'unshare'])->name('chats.unshare');
    Route::post('/chats/{chat}/switch-model', [ChatController::class, 'switchModel'])->name('chats.switch-model');
    Route::post('/chats/{chat}/switch-persona', [ChatController::class, 'switchPersona'])->name('chats.switch-persona');
    Route::post('/chats/{chat}/pin', [ChatController::class, 'pin'])->name('chats.pin');
    Route::post('/chats/{chat}/rename', [ChatController::class, 'rename'])->name('chats.rename');
    Route::post('/chats/{chat}/export', [ChatController::class, 'export'])->name('chats.export');

    // Personas
    Route::resource('personas', PersonaController::class);
    Route::post('/personas/{persona}/attach-knowledge', [PersonaController::class, 'attachKnowledge'])->name('personas.attach-knowledge');
    Route::delete('/personas/{persona}/detach-knowledge/{knowledge}', [PersonaController::class, 'detachKnowledge'])->name('personas.detach-knowledge');

    // Prompts
    Route::resource('prompts', PromptController::class);
    Route::resource('prompt-folders', PromptFolderController::class);
    Route::post('/prompts/{prompt}/favorite', [PromptController::class, 'favorite'])->name('prompts.favorite');
    Route::delete('/prompts/{prompt}/unfavorite', [PromptController::class, 'unfavorite'])->name('prompts.unfavorite');
    Route::get('/prompts/export', [PromptController::class, 'export'])->name('prompts.export');
    Route::post('/prompts/import', [PromptController::class, 'import'])->name('prompts.import');
    Route::post('/prompts/{prompt}/share', [PromptController::class, 'share'])->name('prompts.share');
    Route::post('/prompts/{prompt}/unshare', [PromptController::class, 'unshare'])->name('prompts.unshare');

    // Images
    Route::resource('images', ImageController::class);
    Route::post('/images/generate', [ImageController::class, 'generate'])->name('images.generate');
    Route::get('/images/{imageJob}/status', [ImageController::class, 'status'])->name('images.status');
    Route::get('/images/{imageJob}/download', [ImageController::class, 'download'])->name('images.download');
    Route::post('/images/{imageJob}/regenerate', [ImageController::class, 'regenerate'])->name('images.regenerate');
    Route::post('/images/{imageJob}/upscale', [ImageController::class, 'upscale'])->name('images.upscale');

    // Usage
    Route::get('/usage', [UsageController::class, 'index'])->name('usage.index');
    Route::get('/usage/export', [UsageController::class, 'export'])->name('usage.export');

    // Billing
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');
    Route::get('/billing/success', [BillingController::class, 'success'])->name('billing.success');
    Route::get('/billing/cancel', [BillingController::class, 'cancel'])->name('billing.cancel');

    // Import
    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/import', [ImportController::class, 'store'])->name('import.store');

    // AI Testing & Monitoring (rate limited)
    Route::middleware(['throttle:30,1'])->group(function () {
        Route::post('/ai/test', [AIController::class, 'testResponse'])->name('ai.test');
    });
    Route::get('/ai/logs', [AIController::class, 'getLogs'])->name('ai.logs');
    Route::get('/ai/stats', [AIController::class, 'getStats'])->name('ai.stats');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public billing routes (no authentication required)
Route::get('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');
Route::post('/billing/checkout', [BillingController::class, 'checkout'])->name('billing.checkout');

// API routes
Route::prefix('api')->group(function () {
    Route::post('/export/docx', [ChatController::class, 'exportDocx'])->name('api.export.docx');
    Route::post('/export/pdf', [ChatController::class, 'exportPdf'])->name('api.export.pdf');

    // Webhooks
    Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('api.webhooks.stripe');
});

require __DIR__.'/auth.php';
