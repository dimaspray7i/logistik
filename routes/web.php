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
    
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
        Route::resource('contacts', \App\Http\Controllers\Admin\ContactController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)->except(['show']);
        Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::resource('vehicles', \App\Http\Controllers\Admin\VehicleController::class)->except(['show']);
        Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class)->except(['show']);
        Route::resource('shipments', \App\Http\Controllers\Admin\ShipmentController::class);
        Route::get('/shipments/{shipment}/route', [\App\Http\Controllers\Admin\RouteController::class, 'edit'])->name('shipments.route.edit');
        Route::post('/shipments/{shipment}/route', [\App\Http\Controllers\Admin\RouteController::class, 'store'])->name('shipments.route.store');
        Route::post('/shipments/{shipment}/tracking', [\App\Http\Controllers\Admin\TrackingController::class, 'store'])->name('shipments.tracking.store');
        Route::delete('/shipments/{shipment}/tracking/{trackingUpdate}', [\App\Http\Controllers\Admin\TrackingController::class, 'destroy'])->name('shipments.tracking.destroy');
        Route::post('/shipments/{shipment}/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'store'])->name('shipments.documents.store');
        Route::delete('/shipments/{shipment}/documents/{document}', [\App\Http\Controllers\Admin\DocumentController::class, 'destroy'])->name('shipments.documents.destroy');
});

// ==========================================
// ROUTE GROUP: CUSTOMER
// ==========================================
Route::middleware(['auth', 'role.customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
});