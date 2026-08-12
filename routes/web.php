<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Route Welcome (Halaman Depan)
Route::get('/', function () {
    return view('welcome');
});

// 2. Route Auth Breeze (Login, Register, dll)
require __DIR__.'/auth.php';

// 3. Route Jembatan Dashboard (Redirect berdasarkan Role)
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isCustomer()) {
        return redirect()->route('customer.dashboard');
    }

    return redirect('/');
})->name('dashboard');

// 4. Route Profile Breeze (Edit nama, email, password)
//    Dipakai oleh dropdown menu di navigation.blade.php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// ROUTE GROUP: ADMIN
// ==========================================
Route::middleware(['auth', 'role.admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ==========================================
// ROUTE GROUP: CUSTOMER
// ==========================================
Route::middleware(['auth', 'role.customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
});