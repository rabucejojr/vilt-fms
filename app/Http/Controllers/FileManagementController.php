<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagementController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $files = File::where('user_id', $user->id)
            ->with('folder')
            ->latest()
            ->take(10)
            ->get();

        $folders = Folder::where('user_id', $user->id)
            ->whereNull('parent_id')
            ->withCount('files')
            ->withCount('children')
            ->latest()
            ->take(5)
            ->get();

        $recentFiles = File::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_files' => File::where('user_id', $user->id)->count(),
            'total_folders' => Folder::where('user_id', $user->id)->count(),
            'total_size' => File::where('user_id', $user->id)->sum('size'),
            'public_files' => File::where('user_id', $user->id)->where('is_public', true)->count(),
        ];

        return view('file-management.dashboard', compact('files', 'folders', 'recentFiles', 'stats'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $folderId = $request->get('folder_id');

        $currentFolder = null;
        if ($folderId) {
            $currentFolder = Folder::where('user_id', $user->id)
                ->where('id', $folderId)
                ->firstOrFail();
        }

        $files = File::where('user_id', $user->id)
            ->when($folderId, function ($query) use ($folderId) {
                return $query->where('folder_id', $folderId);
            })
            ->when(!$folderId, function ($query) {
                return $query->whereNull('folder_id');
            })
            ->with('folder')
            ->latest()
            ->paginate(20);

        $folders = Folder::where('user_id', $user->id)
            ->where('parent_id', $folderId)
            ->withCount('files')
            ->latest()
            ->get();

        return view('file-management.index', compact('files', 'folders', 'currentFolder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'folder_id' => 'nullable|exists:folders,id',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean'
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize();
        $type = $file->getMimeType();

        $fileName = Str::random(40) . '.' . $extension;
        $path = $file->storeAs('files', $fileName, 'public');

        File::create([
            'name' => pathinfo($originalName, PATHINFO_FILENAME),
            'original_name' => $originalName,
            'path' => $path,
            'size' => $size,
            'type' => $type,
            'extension' => $extension,
            'user_id' => Auth::id(),
            'folder_id' => $request->folder_id,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false),
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }

    public function destroy(File $file)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        Storage::disk('public')->delete($file->path);
        $file->delete();

        return redirect()->back()->with('success', 'File deleted successfully!');
    }

    public function download(File $file)
    {
        if ($file->user_id !== Auth::id() && !$file->is_public) {
            abort(403);
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }
}
