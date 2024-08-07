<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotesController;
use App\Http\Controllers\FilesController;

Route::view('/', 'welcome');
Route::redirect('dashboard', '/notes');
Route::get('notes', [NotesController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('notes');
Route::post('/upload', [FilesController::class, 'store'])->name('upload');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
