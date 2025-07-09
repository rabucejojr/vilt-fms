<?php

use App\Http\Controllers\FileManagementController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [FileManagementController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // File Management Routes
    Route::get('/files', [FileManagementController::class, 'index'])->name('files.index');
    Route::post('/files', [FileManagementController::class, 'store'])->name('files.store');
    Route::delete('/files/{file}', [FileManagementController::class, 'destroy'])->name('files.destroy');
    Route::get('/files/{file}/download', [FileManagementController::class, 'download'])->name('files.download');

    // Folder Management Routes
    Route::post('/folders', [FolderController::class, 'store'])->name('folders.store');
    Route::delete('/folders/{folder}', [FolderController::class, 'destroy'])->name('folders.destroy');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
