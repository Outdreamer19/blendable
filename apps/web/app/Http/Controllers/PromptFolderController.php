<?php

namespace App\Http\Controllers;

use App\Models\PromptFolder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PromptFolderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        $folders = PromptFolder::where('workspace_id', $currentWorkspace->id)
            ->where('user_id', $user->id)
            ->get();

        return Inertia::render('PromptFolders/Index', [
            'folders' => $folders,
        ]);
    }

    public function create()
    {
        return Inertia::render('PromptFolders/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $user = Auth::user();
        $currentWorkspace = $user->currentWorkspace();

        PromptFolder::create([
            'user_id' => $user->id,
            'workspace_id' => $currentWorkspace->id,
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color ?? '#3B82F6',
        ]);

        return redirect()->route('prompt-folders.index')
            ->with('success', 'Folder created successfully.');
    }

    public function show(PromptFolder $promptFolder)
    {
        $this->authorize('view', $promptFolder);

        return Inertia::render('PromptFolders/Show', [
            'folder' => $promptFolder->load('prompts'),
        ]);
    }

    public function edit(PromptFolder $promptFolder)
    {
        $this->authorize('update', $promptFolder);

        return Inertia::render('PromptFolders/Edit', [
            'folder' => $promptFolder,
        ]);
    }

    public function update(Request $request, PromptFolder $promptFolder)
    {
        $this->authorize('update', $promptFolder);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
        ]);

        $promptFolder->update($request->only(['name', 'description', 'color']));

        return redirect()->route('prompt-folders.index')
            ->with('success', 'Folder updated successfully.');
    }

    public function destroy(PromptFolder $promptFolder)
    {
        $this->authorize('delete', $promptFolder);

        $promptFolder->delete();

        return redirect()->route('prompt-folders.index')
            ->with('success', 'Folder deleted successfully.');
    }
}
