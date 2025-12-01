<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DynamicQrController;
use App\Http\Controllers\RedirectController;

Route::get('/', function() {
    return redirect()->route('qr.index');
});

// CRUD QR Dinamis
Route::resource('qr', DynamicQrController::class);

// Route untuk redirect QR
Route::get('/r/{code}', [RedirectController::class, 'go'])->name('qr.redirect');
