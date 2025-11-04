<?php

namespace Tests\Feature;

use App\Models\ImageJob;
use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImagesTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Team $team;

    protected Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->team = Team::factory()->create();
        $this->workspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);

        // Attach user to team and workspace
        $this->user->teams()->attach($this->team, ['role' => 'admin']);
        $this->user->workspaces()->attach($this->workspace, ['role' => 'admin']);
        $this->user->update(['current_workspace_id' => $this->workspace->id]);

        Storage::fake('public');
    }

    public function test_images_index_displays_all_image_jobs()
    {
        // Create test image jobs
        $imageJobs = ImageJob::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Index')
            ->has('imageJobs')
            ->where('imageJobs', function ($jobs) {
                return count($jobs) === 5;
            })
        );
    }

    public function test_images_index_shows_job_status()
    {
        // Create image jobs with different statuses
        ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'completed',
        ]);

        ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'pending',
        ]);

        ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'failed',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Index')
            ->has('imageJobs')
            ->where('imageJobs', function ($jobs) {
                $statuses = collect($jobs)->pluck('status')->toArray();

                return in_array('completed', $statuses) &&
                       in_array('pending', $statuses) &&
                       in_array('failed', $statuses);
            })
        );
    }

    public function test_images_create_page_loads()
    {
        $response = $this->actingAs($this->user)
            ->get(route('images.create'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Create')
            ->has('user')
            ->has('workspaces')
            ->has('currentWorkspace')
            ->has('availableModels')
        );
    }

    public function test_images_can_be_generated()
    {
        $imageData = [
            'prompt' => 'A beautiful sunset over mountains',
            'model' => 'dall-e-3',
            'size' => '1024x1024',
            'quality' => 'standard',
            'style' => 'vivid',
        ];

        $response = $this->actingAs($this->user)
            ->post(route('images.store'), $imageData);

        $response->assertRedirect(route('images.index'));

        $this->assertDatabaseHas('image_jobs', [
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'prompt' => 'A beautiful sunset over mountains',
            'model' => 'dall-e-3',
        ]);
    }

    public function test_images_require_authentication()
    {
        $response = $this->get(route('images.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_images_require_workspace_access()
    {
        // Create another workspace
        $otherWorkspace = Workspace::factory()->create([
            'team_id' => $this->team->id,
        ]);

        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $otherWorkspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.show', $imageJob));

        $response->assertStatus(403);
    }

    public function test_images_can_be_viewed()
    {
        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.show', $imageJob));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Show')
            ->has('imageJob')
            ->where('imageJob.id', $imageJob->id)
        );
    }

    public function test_images_can_be_deleted()
    {
        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->delete(route('images.destroy', $imageJob));

        $response->assertRedirect(route('images.index'));

        $this->assertDatabaseMissing('image_jobs', [
            'id' => $imageJob->id,
        ]);
    }

    public function test_images_validation_works()
    {
        $response = $this->actingAs($this->user)
            ->post(route('images.store'), []);

        $response->assertSessionHasErrors(['prompt', 'model']);
    }

    public function test_images_status_endpoint()
    {
        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.status', $imageJob));

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $imageJob->id,
            'status' => 'completed',
        ]);
    }

    public function test_images_download_endpoint()
    {
        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'completed',
            'image_url' => 'https://example.com/image.jpg',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.download', $imageJob));

        $response->assertStatus(200);
    }

    public function test_images_regenerate_endpoint()
    {
        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('images.regenerate', $imageJob));

        $response->assertRedirect();

        // Should create a new image job
        $this->assertDatabaseHas('image_jobs', [
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'prompt' => $imageJob->prompt,
        ]);
    }

    public function test_images_upscale_endpoint()
    {
        $imageJob = ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'completed',
        ]);

        $response = $this->actingAs($this->user)
            ->post(route('images.upscale', $imageJob));

        $response->assertRedirect();

        // Should create a new upscaled image job
        $this->assertDatabaseHas('image_jobs', [
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'parent_id' => $imageJob->id,
        ]);
    }

    public function test_images_filter_by_status()
    {
        // Create jobs with different statuses
        ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'completed',
        ]);

        ImageJob::factory()->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.index', ['status' => 'completed']));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Index')
            ->has('imageJobs')
            ->where('imageJobs', function ($jobs) {
                return collect($jobs)->every(fn ($job) => $job['status'] === 'completed');
            })
        );
    }

    public function test_images_pagination()
    {
        // Create more than 20 image jobs
        ImageJob::factory()->count(25)->create([
            'user_id' => $this->user->id,
            'workspace_id' => $this->workspace->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('images.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Images/Index')
            ->has('imageJobs')
            ->where('imageJobs', function ($jobs) {
                return count($jobs) <= 20; // Should be paginated
            })
        );
    }
}
