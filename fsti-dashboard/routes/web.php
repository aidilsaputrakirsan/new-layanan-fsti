<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Public\FormController as PublicFormController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'message' => 'Laravel 12 + Inertia.js + Vue 3 berhasil dikonfigurasi!'
    ]);
});

// Public form routes
Route::get('/form/{slug}', [PublicFormController::class, 'show'])->name('public.form.show');
Route::post('/form/{form}/submit', [PublicFormController::class, 'submit'])->name('public.form.submit');

// Guest routes (unauthenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('Admin/Dashboard');
        })->name('dashboard');
        
        // Categories
        Route::resource('categories', CategoryController::class);
        Route::patch('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');
        
        // Forms
        Route::resource('forms', FormController::class);
        Route::patch('/forms/{form}/toggle-status', [FormController::class, 'toggleStatus'])
            ->name('forms.toggle-status');
    });
});
