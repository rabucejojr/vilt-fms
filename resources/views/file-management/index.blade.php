<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
            <div class="flex items-center space-x-2">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    @if ($currentFolder)
                        {{ $currentFolder->name }}
                    @else
                        {{ __('All Files') }}
                    @endif
                </h2>
            </div>
            <div class="flex space-x-2">
                <button onclick="openUploadModal()"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                    <i class="fas fa-upload mr-1"></i> Upload
                </button>
                <button onclick="openCreateFolderModal()"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                    <i class="fas fa-folder-plus mr-1"></i> New Folder
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            @if ($currentFolder)
                <div class="mb-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('files.index') }}"
                                    class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-home mr-2"></i>
                                    Home
                                </a>
                            </li>
                            @foreach ($currentFolder->full_path_array ?? [] as $path)
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="text-sm font-medium text-gray-500">{{ $path }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                </div>
            @endif

            <!-- Folders Grid -->
            @if ($folders->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Folders</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach ($folders as $folder)
                            <div
                                class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <a href="{{ route('files.index', ['folder_id' => $folder->id]) }}"
                                    class="block p-4 text-center">
                                    <i class="fas fa-folder text-yellow-500 text-3xl mb-2"></i>
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $folder->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $folder->files_count }} files</p>
                                </a>
                                <div class="border-t border-gray-100 p-2">
                                    <div class="flex justify-center space-x-1">
                                        <button onclick="deleteFolder({{ $folder->id }})"
                                            class="text-red-500 hover:text-red-700 text-xs">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Files Grid/List Toggle -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Files</h3>
                <div class="flex items-center space-x-2">
                    <button id="gridView" onclick="setView('grid')"
                        class="p-2 text-blue-500 border border-blue-500 rounded">
                        <i class="fas fa-th"></i>
                    </button>
                    <button id="listView" onclick="setView('list')"
                        class="p-2 text-gray-500 border border-gray-300 rounded">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <!-- Files Grid View -->
            <div id="filesGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
                @foreach ($files as $file)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-4 text-center">
                            <i class="{{ $file->icon }} text-3xl mb-2"></i>
                            <p class="text-sm font-medium text-gray-900 truncate" title="{{ $file->name }}">
                                {{ $file->name }}</p>
                            <p class="text-xs text-gray-500">{{ $file->formatted_size }}</p>
                            <p class="text-xs text-gray-400">{{ $file->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="border-t border-gray-100 p-2">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('files.download', $file) }}"
                                    class="text-blue-500 hover:text-blue-700 text-xs" title="Download">
                                    <i class="fas fa-download"></i>
                                </a>
                                <button onclick="deleteFile({{ $file->id }})"
                                    class="text-red-500 hover:text-red-700 text-xs" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Files List View -->
            <div id="filesList" class="hidden">
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        File</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Size</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Modified</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($files as $file)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <i class="{{ $file->icon }} text-xl mr-3"></i>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $file->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $file->original_name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $file->formatted_size }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $file->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('files.download', $file) }}"
                                                    class="text-blue-500 hover:text-blue-700">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button onclick="deleteFile({{ $file->id }})"
                                                    class="text-red-500 hover:text-red-700">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if ($files->hasPages())
                <div class="mt-6">
                    {{ $files->links() }}
                </div>
            @endif

            @if ($files->count() === 0 && $folders->count() === 0)
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No files or folders</h3>
                    <p class="text-gray-500 mb-4">Get started by uploading a file or creating a folder.</p>
                    <div class="flex justify-center space-x-4">
                        <button onclick="openUploadModal()"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-upload mr-2"></i>Upload File
                        </button>
                        <button onclick="openCreateFolderModal()"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-folder-plus mr-2"></i>Create Folder
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Upload File</h3>
                    <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($currentFolder)
                            <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
                        @endif
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Choose
                                File</label>
                            <input type="file" name="file" id="file" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description
                                (Optional)</label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div class="flex items-center mb-4">
                            <input type="checkbox" name="is_public" id="is_public" value="1" class="mr-2">
                            <label for="is_public" class="text-sm text-gray-700">Make file public</label>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeUploadModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                Upload
                            </button>
                        </div>
                    </form>
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
                        @if ($currentFolder)
                            <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
                        @endif
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
                            <input type="checkbox" name="is_public" id="folder_public" class="mr-2">
                            <label for="folder_public" class="text-sm text-gray-700">Make folder public</label>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeCreateFolderModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">
                                Create Folder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openUploadModal() {
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }

        function openCreateFolderModal() {
            document.getElementById('createFolderModal').classList.remove('hidden');
        }

        function closeCreateFolderModal() {
            document.getElementById('createFolderModal').classList.add('hidden');
        }

        function setView(view) {
            const gridView = document.getElementById('filesGrid');
            const listView = document.getElementById('filesList');
            const gridBtn = document.getElementById('gridView');
            const listBtn = document.getElementById('listView');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                listView.classList.add('hidden');
                gridBtn.classList.add('text-blue-500', 'border-blue-500');
                gridBtn.classList.remove('text-gray-500', 'border-gray-300');
                listBtn.classList.add('text-gray-500', 'border-gray-300');
                listBtn.classList.remove('text-blue-500', 'border-blue-500');
            } else {
                gridView.classList.add('hidden');
                listView.classList.remove('hidden');
                listBtn.classList.add('text-blue-500', 'border-blue-500');
                listBtn.classList.remove('text-gray-500', 'border-gray-300');
                gridBtn.classList.add('text-gray-500', 'border-gray-300');
                gridBtn.classList.remove('text-blue-500', 'border-blue-500');
            }
        }

        function deleteFile(fileId) {
            if (confirm('Are you sure you want to delete this file?')) {
                fetch(`/files/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                }).then(() => {
                    window.location.reload();
                });
            }
        }

        function deleteFolder(folderId) {
            if (confirm('Are you sure you want to delete this folder? This will delete all files in the folder.')) {
                fetch(`/folders/${folderId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                }).then(() => {
                    window.location.reload();
                });
            }
        }

        // Close modals when clicking outside
        document.getElementById('uploadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
            }
        });

        document.getElementById('createFolderModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCreateFolderModal();
            }
        });
    </script>
</x-app-layout>
