<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\SubmissionController;
use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Support\Facades\Route;

// Public intake form
Route::get('/', [SubmissionController::class, 'index'])->name('form.index');
Route::post('/submissions', [SubmissionController::class, 'store'])->name('form.store');

// File uploads
Route::post('/uploads', [FileUploadController::class, 'store'])->name('uploads.store');
Route::delete('/uploads/{fileUpload}', [FileUploadController::class, 'destroy'])->name('uploads.destroy');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', EnsureIsAdmin::class])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/submissions/{submission}', [DashboardController::class, 'show'])->name('submissions.show');
    });
});
