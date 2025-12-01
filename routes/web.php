<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicQrController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::get('/', function() {
    return redirect()->route('qr.index');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// CRUD QR Dinamis
Route::middleware('auth')->group(function () {
    Route::get('qr/{id}/download', [DynamicQrController::class, 'download'])->name('qr.download');
    Route::resource('qr', DynamicQrController::class);
});

// Admin dashboard & manajemen user
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});

// Route untuk redirect QR
Route::get('/r/{code}', [RedirectController::class, 'go'])->name('qr.redirect');
