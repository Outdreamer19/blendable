<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateImageJob;
use App\Models\ImageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ImageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $imageJobs = ImageJob::where('workspace_id', $currentWorkspace->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get available models for the create form
        $availableModels = [
            ['value' => 'dall-e-3', 'label' => 'DALL-E 3', 'description' => 'Latest OpenAI image model'],
            ['value' => 'dall-e-2', 'label' => 'DALL-E 2', 'description' => 'Previous OpenAI image model'],
            ['value' => 'stable-diffusion', 'label' => 'Stable Diffusion', 'description' => 'Open source image model'],
        ];

        return Inertia::render('Images/Index', [
            'user' => $user,
            'images' => $imageJobs,
            'workspaces' => $user->workspaces()->with('team')->get(),
            'workspace' => $currentWorkspace,
            'availableModels' => $availableModels,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        return Inertia::render('Images/Create', [
            'workspace' => $currentWorkspace,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $request->validate([
            'prompt' => 'required|string|max:1000',
            'model' => 'required|in:dall-e-3,dall-e-2,stable-diffusion',
            'size' => 'required|in:1024x1024,1024x1792,1792x1024,512x512',
            'quality' => 'in:standard,hd',
            'style' => 'in:vivid,natural',
        ]);

        $imageJob = ImageJob::create([
            'user_id' => $user->id,
            'workspace_id' => $currentWorkspace->id,
            'prompt' => $request->prompt,
            'model' => $request->model,
            'size' => $request->size,
            'quality' => $request->quality ?? 'standard',
            'style' => $request->style ?? 'vivid',
            'status' => 'pending',
        ]);

        // Queue the image generation job
        GenerateImageJob::dispatch($imageJob);

        return redirect()->route('images.show', $imageJob)
            ->with('success', 'Image generation started. You will be notified when it\'s ready.');
    }

    public function show(ImageJob $imageJob)
    {
        $this->authorize('view', $imageJob);

        return Inertia::render('Images/Show', [
            'imageJob' => $imageJob,
        ]);
    }

    public function regenerate(ImageJob $imageJob)
    {
        $this->authorize('update', $imageJob);

        // Create a new job with the same parameters
        $newImageJob = ImageJob::create([
            'user_id' => $imageJob->user_id,
            'workspace_id' => $imageJob->workspace_id,
            'prompt' => $imageJob->prompt,
            'model' => $imageJob->model,
            'size' => $imageJob->size,
            'quality' => $imageJob->quality,
            'style' => $imageJob->style,
            'status' => 'pending',
        ]);

        GenerateImageJob::dispatch($newImageJob);

        return redirect()->route('images.show', $newImageJob)
            ->with('success', 'Image regeneration started.');
    }

    public function upscale(ImageJob $imageJob)
    {
        $this->authorize('update', $imageJob);

        if (! $imageJob->isCompleted()) {
            return back()->with('error', 'Image must be completed before upscaling.');
        }

        // Create a new job for upscaling
        $upscaleJob = ImageJob::create([
            'user_id' => $imageJob->user_id,
            'workspace_id' => $imageJob->workspace_id,
            'prompt' => $imageJob->prompt.' (upscaled)',
            'model' => 'upscale',
            'size' => '2048x2048',
            'quality' => 'hd',
            'style' => $imageJob->style,
            'status' => 'pending',
            'metadata' => ['original_job_id' => $imageJob->id],
        ]);

        // Queue upscaling job (would need separate upscaling implementation)
        // UpscaleImageJob::dispatch($upscaleJob, $imageJob->image_url);

        return redirect()->route('images.show', $upscaleJob)
            ->with('success', 'Image upscaling started.');
    }

    public function destroy(ImageJob $imageJob)
    {
        $this->authorize('delete', $imageJob);

        // Delete the image file if it exists
        if ($imageJob->image_url) {
            $filename = str_replace('/storage/', '', $imageJob->image_url);
            \Storage::disk('public')->delete($filename);
        }

        $imageJob->delete();

        return redirect()->route('images.index')
            ->with('success', 'Image deleted successfully.');
    }

    public function status(ImageJob $imageJob)
    {
        $this->authorize('view', $imageJob);

        return response()->json([
            'status' => $imageJob->status,
            'image_url' => $imageJob->image_url,
            'error_message' => $imageJob->error_message,
            'metadata' => $imageJob->metadata,
        ]);
    }

    public function download(ImageJob $imageJob)
    {
        $this->authorize('view', $imageJob);

        if (! $imageJob->isCompleted() || ! $imageJob->image_url) {
            return back()->with('error', 'Image is not ready for download.');
        }

        $filename = str_replace('/storage/', '', $imageJob->image_url);
        $path = storage_path('app/public/'.$filename);

        if (! file_exists($path)) {
            return back()->with('error', 'Image file not found.');
        }

        return response()->download($path, 'image-'.$imageJob->id.'.png');
    }
}
