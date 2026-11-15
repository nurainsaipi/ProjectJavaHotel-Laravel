<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RoomController;

// ── Public Routes ──
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/check', [HomeController::class, 'check'])->name('check');

// ── Booking ──
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::post('/booking/upload-proof', [BookingController::class, 'uploadProof'])->name('booking.upload-proof');

// ── Auth ──
Route::get('/login', [AdminController::class, 'loginForm'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.post');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// ── Admin (protected) ──
Route::middleware('auth.admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reservations', [AdminController::class, 'reservations'])->name('reservations');
    Route::post('/pay', [AdminController::class, 'pay'])->name('pay');
    Route::post('/reject-proof', [AdminController::class, 'rejectProof'])->name('reject-proof');
    Route::post('/checkout', [AdminController::class, 'checkout'])->name('checkout');

    Route::get('/rooms', [AdminController::class, 'rooms'])->name('rooms');
    Route::post('/rooms/status', [AdminController::class, 'updateStatus'])->name('rooms.status');

    Route::get('/manage-rooms', [RoomController::class, 'index'])->name('manage-rooms');
    Route::post('/manage-rooms', [RoomController::class, 'store'])->name('manage-rooms.store');
    Route::put('/manage-rooms/{room}', [RoomController::class, 'update'])->name('manage-rooms.update');
    Route::delete('/manage-rooms/{room}', [RoomController::class, 'destroy'])->name('manage-rooms.destroy');

    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});