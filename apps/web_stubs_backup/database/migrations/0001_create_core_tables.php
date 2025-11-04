<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('teams', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->timestamps();
        });
        Schema::create('team_user', function (Blueprint $t) {
            $t->id(); $t->foreignId('team_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('role')->default('member');
            $t->timestamps();
        });
        Schema::create('workspaces', function (Blueprint $t) {
            $t->id(); $t->foreignId('team_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->string('slug')->unique(); $t->timestamps();
        });
        Schema::create('chats', function (Blueprint $t) {
            $t->id(); $t->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $t->foreignId('persona_id')->nullable();
            $t->string('title')->nullable();
            $t->string('share_token')->nullable()->unique();
            $t->boolean('is_shared_view_only')->default(false);
            $t->timestamps();
        });
        Schema::create('messages', function (Blueprint $t) {
            $t->id(); $t->foreignId('chat_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable();
            $t->enum('role', ['system','user','assistant','tool']);
            $t->string('model_key')->nullable();
            $t->integer('tokens_in')->default(0);
            $t->integer('tokens_out')->default(0);
            $t->integer('words_out')->default(0);
            $t->json('tool_calls_json')->nullable();
            $t->json('meta_json')->nullable();
            $t->longText('content')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('chats');
        Schema::dropIfExists('workspaces');
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
};
