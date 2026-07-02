<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentFileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('roles', RoleController::class)->except('show')
        ->middleware('permission:roles.manage');

    Route::resource('users', UserController::class)->only(['index', 'store', 'edit', 'update', 'destroy'])
        ->middleware('permission:users.manage');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index')
        ->middleware('permission:settings.manage');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update')
        ->middleware('permission:settings.manage');

    Route::resource('documents', DocumentController::class)
        ->middleware('permission:documents.view');

    Route::post('documents/{document}/files', [DocumentFileController::class, 'store'])->name('documents.files.store');
    Route::get('documents/files/{file}/download', [DocumentFileController::class, 'download'])->name('documents.files.download');
    Route::delete('documents/files/{file}', [DocumentFileController::class, 'destroy'])->name('documents.files.destroy');
});

require __DIR__.'/auth.php';
