@php
    $currentFolder = $currentFolder ?? null;
    $files = $files ?? collect();
    $folders = $folders ?? collect();
@endphp

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
                        All Files
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
            <!-- Drag and Drop Zone -->
            <div id="dragDropZone"
                class="mb-6 p-4 sm:p-8 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors duration-200 text-center relative">
                <div id="dragDropContent">
                    <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400 mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                        </path>
                    </svg>
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">Drop files here to upload</h3>
                    <p class="text-gray-600 mb-4 text-sm">Drag and drop files here, or <button
                            onclick="openUploadModal()" class="text-blue-600 hover:text-blue-800 font-medium">browse
                            files</button></p>
                    <div class="text-xs sm:text-sm text-gray-500">
                        <p>Maximum file size: 10MB</p>
                        <p>Supported formats: All file types</p>
                    </div>
                </div>
                <div id="dragDropOverlay"
                    class="hidden absolute inset-0 bg-blue-500 bg-opacity-10 border-2 border-blue-500 rounded-xl flex items-center justify-center">
                    <div class="text-center">
                        <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-blue-500 mb-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                            </path>
                        </svg>
                        <h3 class="text-base sm:text-lg font-medium text-blue-900">Drop files to upload</h3>
                    </div>
                </div>
            </div>

            <!-- Upload Progress -->
            <div id="uploadProgress" class="hidden mb-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Uploading files...</h4>
                    <div id="uploadProgressBar" class="w-full bg-gray-200 rounded-full h-2 mb-2">
                        <div id="uploadProgressFill" class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                            style="width: 0%"></div>
                    </div>
                    <div id="uploadProgressText" class="text-sm text-gray-600">0%</div>
                </div>
            </div>

            <!-- Breadcrumb -->
            @if ($currentFolder)
                <div class="mb-6">
                    <nav class="flex flex-wrap text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('files.index') }}"
                                    class="inline-flex items-center text-gray-700 hover:text-blue-600">
                                    <i class="fas fa-home mr-2"></i>
                                    Home
                                </a>
                            </li>
                            @foreach ($currentFolder->full_path_array ?? [] as $path)
                                <li>
                                    <div class="flex items-center">
                                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                        <span class="text-gray-500">{{ $path }}</span>
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
                    <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Folders</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4">
                        @foreach ($folders as $folder)
                            <div
                                class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                                <a href="{{ route('files.index', ['folder_id' => $folder->id]) }}"
                                    class="block p-3 sm:p-4 text-center">
                                    <i class="fas fa-folder text-yellow-500 text-2xl sm:text-3xl mb-2"></i>
                                    <p class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $folder->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $folder->files_count }} files</p>
                                </a>
                                <div class="border-t border-gray-100 p-2 flex justify-center">
                                    <button onclick="deleteFolder({{ $folder->id }})"
                                        class="text-red-500 hover:text-red-700 text-xs">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Files Grid/List Toggle -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-2">
                <h3 class="text-base sm:text-lg font-medium text-gray-900">Files</h3>
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
            <div id="filesGrid"
                class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
                @foreach ($files as $file)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-3 sm:p-4 text-center">
                            <i class="{{ $file->icon }} text-2xl sm:text-3xl mb-2"></i>
                            <p class="text-xs sm:text-sm font-medium text-gray-900 truncate"
                                title="{{ $file->name }}">
                                {{ $file->name }}</p>
                            <p class="text-xs text-gray-500">{{ $file->formatted_size }}</p>
                            <p class="text-xs text-gray-400">{{ $file->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="border-t border-gray-100 p-2 flex justify-center space-x-2">
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
                @endforeach
            </div>

            <!-- Files List View -->
            <div id="filesList" class="hidden">
                <div class="bg-white shadow-sm rounded-lg overflow-x-auto">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-2 sm:px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        File</th>
                                    <th
                                        class="px-2 sm:px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Size</th>
                                    <th
                                        class="px-2 sm:px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Modified</th>
                                    <th
                                        class="px-2 sm:px-6 py-3 text-left font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($files as $file)
                                    <tr>
                                        <td class="px-2 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <i class="{{ $file->icon }} text-xl sm:text-2xl mr-2 sm:mr-3"></i>
                                                <div>
                                                    <div class="font-medium text-gray-900 truncate">
                                                        {{ $file->name }}</div>
                                                    @if ($file->description)
                                                        <div class="text-xs text-gray-500 truncate">
                                                            {{ $file->description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-gray-500">
                                            {{ $file->formatted_size }}
                                        </td>
                                        <td class="px-2 sm:px-6 py-4 whitespace-nowrap text-gray-500">
                                            {{ $file->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-2 sm:px-6 py-4 whitespace-nowrap font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('files.download', $file) }}"
                                                    class="text-blue-600 hover:text-blue-900">Download</a>
                                                <button onclick="deleteFile({{ $file->id }})"
                                                    class="text-red-600 hover:text-red-900">Delete</button>
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
                    <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
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

        <!-- Upload Modal -->
        <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-xs sm:max-w-md w-full">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Upload File</h3>
                        <form action="{{ route('files.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @if ($currentFolder)
                                <input type="hidden" name="folder_id" value="{{ $currentFolder->id }}">
                            @endif
                            <div class="mb-4">
                                <label for="file"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Choose File</label>
                                <input type="file" name="file" id="file" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="description"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Description
                                    (Optional)</label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs sm:text-sm"></textarea>
                            </div>
                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="is_public" id="is_public" value="1"
                                    class="mr-2">
                                <label for="is_public" class="text-xs sm:text-sm text-gray-700">Make file
                                    public</label>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeUploadModal()"
                                    class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-xs sm:text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600">
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
            <div class="flex items-center justify-center min-h-screen p-2 sm:p-4">
                <div class="bg-white rounded-lg shadow-xl max-w-xs sm:max-w-md w-full">
                    <div class="p-4 sm:p-6">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Create New Folder</h3>
                        <form action="{{ route('folders.store') }}" method="POST">
                            @csrf
                            @if ($currentFolder)
                                <input type="hidden" name="parent_id" value="{{ $currentFolder->id }}">
                            @endif
                            <div class="mb-4">
                                <label for="folder_name"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Folder Name</label>
                                <input type="text" name="name" id="folder_name" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs sm:text-sm">
                            </div>
                            <div class="mb-4">
                                <label for="folder_description"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Description
                                    (Optional)</label>
                                <textarea name="description" id="folder_description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs sm:text-sm"></textarea>
                            </div>
                            <div class="flex items-center mb-4">
                                <input type="checkbox" name="is_public" id="folder_public" class="mr-2">
                                <label for="folder_public" class="text-xs sm:text-sm text-gray-700">Make folder
                                    public</label>
                            </div>
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeCreateFolderModal()"
                                    class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-xs sm:text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600">
                                    Create Folder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Drag and Drop functionality
            const dragDropZone = document.getElementById('dragDropZone');
            const dragDropContent = document.getElementById('dragDropContent');
            const dragDropOverlay = document.getElementById('dragDropOverlay');
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadProgressFill = document.getElementById('uploadProgressFill');
            const uploadProgressText = document.getElementById('uploadProgressText');

            // File validation settings
            const maxFileSize = 10 * 1024 * 1024; // 10MB
            const allowedFileTypes = [
                'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                'application/pdf', 'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                'text/plain', 'text/csv', 'application/zip', 'application/x-rar-compressed',
                'video/mp4', 'video/avi', 'video/mov', 'audio/mpeg', 'audio/wav'
            ];

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dragDropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop zone when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dragDropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dragDropZone.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            dragDropZone.addEventListener('drop', handleDrop, false);

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight(e) {
                dragDropZone.classList.add('border-blue-500', 'bg-blue-50');
                dragDropOverlay.classList.remove('hidden');
            }

            function unhighlight(e) {
                dragDropZone.classList.remove('border-blue-500', 'bg-blue-50');
                dragDropOverlay.classList.add('hidden');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            function validateFile(file) {
                const errors = [];

                // Check file size
                if (file.size > maxFileSize) {
                    errors.push(`${file.name} is too large. Maximum size is 10MB.`);
                }

                // Check file type
                if (!allowedFileTypes.includes(file.type) && file.type !== '') {
                    errors.push(`${file.name} has an unsupported file type.`);
                }

                return errors;
            }

            function handleFiles(files) {
                if (files.length === 0) return;

                // Validate all files first
                let allErrors = [];
                let validFiles = [];

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const errors = validateFile(file);

                    if (errors.length > 0) {
                        allErrors = allErrors.concat(errors);
                    } else {
                        validFiles.push(file);
                    }
                }

                // Show errors if any
                if (allErrors.length > 0) {
                    const errorMessage = allErrors.join('\n');
                    alert('Some files could not be uploaded:\n\n' + errorMessage);

                    // If no valid files, return early
                    if (validFiles.length === 0) {
                        return;
                    }
                }

                // Show progress bar
                uploadProgress.classList.remove('hidden');

                const formData = new FormData();
                let totalFiles = validFiles.length;
                let uploadedFiles = 0;

                // Add valid files to FormData
                for (let i = 0; i < validFiles.length; i++) {
                    formData.append('files[]', validFiles[i]);
                }

                // Add folder_id if in a folder
                @if ($currentFolder)
                    formData.append('folder_id', '{{ $currentFolder->id }}');
                @endif

                // Add CSRF token
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                // Simulate progress for better UX
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    uploadProgressFill.style.width = progress + '%';
                    uploadProgressText.textContent = Math.round(progress) + '%';
                }, 200);

                // Upload files
                fetch('{{ route('files.store') }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        clearInterval(progressInterval);

                        if (data.success) {
                            // Update progress to 100%
                            uploadProgressFill.style.width = '100%';
                            uploadProgressText.textContent = '100%';

                            // Show success message
                            showNotification('Files uploaded successfully!', 'success');

                            // Hide progress after a delay
                            setTimeout(() => {
                                uploadProgress.classList.add('hidden');
                                uploadProgressFill.style.width = '0%';
                                uploadProgressText.textContent = '0%';

                                // Reload page to show new files
                                window.location.reload();
                            }, 1500);
                        } else {
                            showNotification('Upload failed: ' + (data.message || 'Unknown error'), 'error');
                            uploadProgress.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        clearInterval(progressInterval);
                        console.error('Error:', error);
                        showNotification('Upload failed. Please try again.', 'error');
                        uploadProgress.classList.add('hidden');
                    });
            }

            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className =
                    `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

                if (type === 'success') {
                    notification.className += ' bg-green-500 text-white';
                } else if (type === 'error') {
                    notification.className += ' bg-red-500 text-white';
                } else {
                    notification.className += ' bg-blue-500 text-white';
                }

                notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success' ? '<i class="fas fa-check-circle"></i>' :
                          type === 'error' ? '<i class="fas fa-exclamation-circle"></i>' :
                          '<i class="fas fa-info-circle"></i>'}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;

                document.body.appendChild(notification);

                // Animate in
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }, 5000);
            }

            // Modal functions
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
