<?php

namespace Tests\Feature;

use App\Models\Chat;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->workspace = Workspace::factory()->create();
        $this->user->workspaces()->attach($this->workspace, ['role' => 'admin']);
    }

    public function test_user_can_view_chat()
    {
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('chats.show', $chat));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Chats')
            ->has('activeChat')
            ->has('availableModels')
        );
    }

    public function test_user_can_create_chat()
    {
        $response = $this->actingAs($this->user)
            ->post(route('chats.store'), [
                'workspace_id' => $this->workspace->id,
                'title' => 'Test Chat',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chats', [
            'workspace_id' => $this->workspace->id,
            'title' => 'Test Chat',
        ]);
    }

    public function test_user_can_send_message()
    {
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('chats.send-message', $chat), [
                'content' => 'Hello, world!',
                'model' => 'gpt-4o-mini',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $this->user->id,
            'role' => 'user',
            'content' => 'Hello, world!',
        ]);
    }

    public function test_user_can_switch_model()
    {
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('chats.switch-model', $chat), [
                'model' => 'claude-3-sonnet-20240229',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('chats', [
            'id' => $chat->id,
            'settings->model' => 'claude-3-sonnet-20240229',
        ]);
    }

    public function test_user_can_share_chat()
    {
        $chat = Chat::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('chats.share', $chat));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'share_token',
            'share_url',
        ]);

        $chat->refresh();
        $this->assertNotNull($chat->share_token);
    }

    public function test_user_cannot_access_other_workspace_chat()
    {
        $otherWorkspace = Workspace::factory()->create();
        $chat = Chat::factory()->create([
            'workspace_id' => $otherWorkspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('chats.show', $chat));

        $response->assertStatus(403);
    }
}
