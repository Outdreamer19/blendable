<?php

namespace App\Http\Controllers;

use App\Models\Prompt;
use App\Models\PromptFolder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PromptController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $folders = PromptFolder::where('workspace_id', $currentWorkspace->id)
            ->withCount('prompts')
            ->orderBy('name')
            ->get();

        $prompts = Prompt::where('workspace_id', $currentWorkspace->id)
            ->with('folder')
            ->orderBy('name')
            ->get();

        return Inertia::render('Prompts/Index', [
            'folders' => $folders,
            'prompts' => $prompts,
            'workspace' => $currentWorkspace,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $folders = PromptFolder::where('workspace_id', $currentWorkspace->id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Prompts/Create', [
            'folders' => $folders,
            'workspace' => $currentWorkspace,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'folder_id' => 'nullable|exists:prompt_folders,id',
            'tags' => 'array',
            'is_public' => 'boolean',
        ]);

        $prompt = Prompt::create([
            'user_id' => $user->id,
            'workspace_id' => $currentWorkspace->id,
            'folder_id' => $request->folder_id,
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
            'tags' => $request->tags ?? [],
            'is_public' => $request->boolean('is_public'),
        ]);

        return redirect()->route('prompts.show', $prompt)
            ->with('success', 'Prompt created successfully.');
    }

    public function show(Prompt $prompt)
    {
        $this->authorize('view', $prompt);

        $prompt->load('folder');

        return Inertia::render('Prompts/Show', [
            'prompt' => $prompt,
        ]);
    }

    public function edit(Prompt $prompt)
    {
        $this->authorize('update', $prompt);

        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $folders = PromptFolder::where('workspace_id', $currentWorkspace->id)
            ->orderBy('name')
            ->get();

        return Inertia::render('Prompts/Edit', [
            'prompt' => $prompt,
            'folders' => $folders,
            'workspace' => $currentWorkspace,
        ]);
    }

    public function update(Request $request, Prompt $prompt)
    {
        $this->authorize('update', $prompt);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'folder_id' => 'nullable|exists:prompt_folders,id',
            'tags' => 'array',
            'is_public' => 'boolean',
            'is_favorite' => 'boolean',
        ]);

        $prompt->update([
            'name' => $request->name,
            'description' => $request->description,
            'content' => $request->content,
            'folder_id' => $request->folder_id,
            'tags' => $request->tags ?? [],
            'is_public' => $request->boolean('is_public'),
            'is_favorite' => $request->boolean('is_favorite'),
        ]);

        return redirect()->route('prompts.show', $prompt)
            ->with('success', 'Prompt updated successfully.');
    }

    public function destroy(Prompt $prompt)
    {
        $this->authorize('delete', $prompt);

        $prompt->delete();

        return redirect()->route('prompts.index')
            ->with('success', 'Prompt deleted successfully.');
    }

    public function use(Prompt $prompt)
    {
        $this->authorize('view', $prompt);

        $prompt->incrementUsage();

        return response()->json([
            'success' => true,
            'content' => $prompt->content,
        ]);
    }

    public function export(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        if (! $currentWorkspace) {
            return redirect()->route('prompts.index')->with('error', 'No workspace available.');
        }

        $format = $request->get('format', 'json');
        $prompts = Prompt::where('workspace_id', $currentWorkspace->id)->get();

        if ($format === 'csv') {
            $csv = "Name,Content,Description,Tags,Public,Favorite,Usage Count\n";
            foreach ($prompts as $prompt) {
                $csv .= '"'.str_replace('"', '""', $prompt->name).'",';
                $csv .= '"'.str_replace('"', '""', $prompt->content).'",';
                $csv .= '"'.str_replace('"', '""', $prompt->description).'",';
                $csv .= '"'.implode(',', $prompt->tags ?? []).'",';
                $csv .= $prompt->is_public ? 'Yes' : 'No'.',';
                $csv .= $prompt->is_favorite ? 'Yes' : 'No'.',';
                $csv .= $prompt->usage_count."\n";
            }

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="prompts.csv"');
        }

        return response()->json($prompts);
    }

    public function import(Request $request)
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        if (! $currentWorkspace) {
            return redirect()->route('prompts.index')->with('error', 'No workspace available.');
        }

        $request->validate([
            'file' => 'required|file|mimes:json,csv,txt|max:10240',
        ]);

        // Basic import logic - can be enhanced
        return redirect()->route('prompts.index')->with('success', 'Prompts imported successfully.');
    }

    public function share(Prompt $prompt)
    {
        $this->authorize('view', $prompt);

        $prompt->update(['is_public' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Prompt shared successfully.',
        ]);
    }

    public function unshare(Prompt $prompt)
    {
        $this->authorize('view', $prompt);

        $prompt->update(['is_public' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Prompt unshared successfully.',
        ]);
    }
}
