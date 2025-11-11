<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Items
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/items/{uuid}', [ItemController::class, 'show'])->name('items.show');
Route::middleware('auth')->group(function () {
    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
});

// Vendors
Route::get('/vendors', [VendorController::class, 'index'])->name('vendors.index');
Route::get('/vendor/{username}', [VendorController::class, 'show'])->name('vendors.show');

// Auth routes
require __DIR__.'/auth.php';
