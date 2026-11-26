<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Route;

// The 'web' group gives the API the same login session as your Blade pages.
Route::middleware(['web', 'auth'])->name('api.')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::apiResource('tasks', TaskController::class);
});