<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FileUploadController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');
    Route::post('/upload', [FileUploadController::class, 'store'])->name('upload.store');

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::resource('/admin/groups', \App\Http\Controllers\GroupController::class);
        Route::resource('/admin/users', \App\Http\Controllers\UserController::class)->except(['show']);
        Route::get('/admin/config', [AdminController::class, 'config'])->name('admin.config');
        Route::post('/admin/config/update', [AdminController::class, 'updateConfig'])->name('admin.config.update');
    });
});