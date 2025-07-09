<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'parent_id' => 'nullable|exists:folders,id',
            'is_public' => 'boolean',
        ]);

        Folder::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'is_public' => $request->boolean('is_public', false),
        ]);

        return redirect()->back()->with('success', 'Folder created successfully!');
    }

    public function destroy(Folder $folder)
    {
        if ($folder->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete all files in the folder
        foreach ($folder->files as $file) {
            Storage::disk('public')->delete($file->path);
            $file->delete();
        }

        // Delete all subfolders recursively
        $this->deleteFolderRecursively($folder);

        return redirect()->back()->with('success', 'Folder deleted successfully!');
    }

    private function deleteFolderRecursively(Folder $folder)
    {
        foreach ($folder->children as $child) {
            $this->deleteFolderRecursively($child);
        }

        $folder->delete();
    }
}
