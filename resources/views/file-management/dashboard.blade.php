<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('File Management Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('files.index') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                    <i class="fas fa-folder-open mr-1"></i> Browse Files
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file text-blue-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Files</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_files'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder text-yellow-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Folders</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['total_folders'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-hdd text-green-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Size</div>
                                <div class="text-2xl font-semibold text-gray-900">
                                    @php
                                        $bytes = $stats['total_size'];
                                        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
                                            $bytes /= 1024;
                                        }
                                        echo round($bytes, 2) . ' ' . $units[$i];
                                    @endphp
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-globe text-purple-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Public Files</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ $stats['public_files'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Upload File -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                            <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data"
                                class="space-y-2">
                                @csrf
                                <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-2">Upload File</p>
                                <input type="file" name="file" class="hidden" id="quick-upload"
                                    onchange="this.form.submit()">
                                <label for="quick-upload"
                                    class="bg-blue-500 hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded cursor-pointer">
                                    Choose File
                                </label>
                            </form>
                        </div>

                        <!-- Create Folder -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                            <button onclick="openCreateFolderModal()" class="w-full">
                                <i class="fas fa-folder-plus text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-2">Create Folder</p>
                                <span
                                    class="bg-green-500 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded">
                                    New Folder
                                </span>
                            </button>
                        </div>

                        <!-- Browse Files -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                            <a href="{{ route('files.index') }}" class="block">
                                <i class="fas fa-search text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-2">Browse Files</p>
                                <span
                                    class="bg-purple-500 hover:bg-purple-700 text-white text-xs font-bold py-2 px-4 rounded">
                                    Explore
                                </span>
                            </a>
                        </div>

                        <!-- Recent Activity -->
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
                            <a href="#recent-files" class="block">
                                <i class="fas fa-clock text-3xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-600 mb-2">Recent Files</p>
                                <span
                                    class="bg-orange-500 hover:bg-orange-700 text-white text-xs font-bold py-2 px-4 rounded">
                                    View
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Files -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Files</h3>
                        @if ($recentFiles->count() > 0)
                            <div class="space-y-3">
                                @foreach ($recentFiles as $file)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="{{ $file->icon }} text-xl"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $file->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $file->formatted_size }} •
                                                    {{ $file->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-1">
                                            <a href="{{ route('files.download', $file) }}"
                                                class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <form action="{{ route('files.destroy', $file) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No files uploaded yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Folders -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Folders</h3>
                        @if ($folders->count() > 0)
                            <div class="space-y-3">
                                @foreach ($folders as $folder)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <i class="fas fa-folder text-yellow-500 text-xl"></i>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $folder->name }}</p>
                                                <p class="text-xs text-gray-500">{{ $folder->files_count }} files •
                                                    {{ $folder->children_count }} subfolders</p>
                                            </div>
                                        </div>
                                        <div class="flex space-x-1">
                                            <a href="{{ route('files.index', ['folder_id' => $folder->id]) }}"
                                                class="text-blue-500 hover:text-blue-700">
                                                <i class="fas fa-folder-open"></i>
                                            </a>
                                            <form action="{{ route('folders.destroy', $folder) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700"
                                                    onclick="return confirm('Are you sure? This will delete all files in the folder.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">No folders created yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div id="createFolderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Create New Folder</h3>
                    <form action="{{ route('folders.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="folder_name" class="block text-sm font-medium text-gray-700 mb-2">Folder
                                Name</label>
                            <input type="text" name="name" id="folder_name" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="folder_description"
                                class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea name="description" id="folder_description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="flex items-center mb-4">
                            <input type="checkbox" name="is_public" id="is_public" value="1" class="mr-2">
                            <label for="is_public" class="text-sm text-gray-700">Make folder public</label>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeCreateFolderModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                Create Folder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCreateFolderModal() {
            document.getElementById('createFolderModal').classList.remove('hidden');
        }

        function closeCreateFolderModal() {
            document.getElementById('createFolderModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('createFolderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateFolderModal();
            }
        });
    </script>
</x-app-layout>
