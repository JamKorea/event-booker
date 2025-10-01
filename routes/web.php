<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WaitlistController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome'); // Landing page
});

// All routes below require authentication
Route::middleware(['auth'])->group(function () {

    //Event routes
    // 1) Attendee & Organiser: can view events (index, show)
    Route::resource('events', EventController::class)->only(['index', 'show']);

    // 2) Organiser only: can manage events (create, store, edit, update, destroy)
    Route::resource('events', EventController::class)
        ->except(['index', 'show'])
        ->middleware('organiser');

    // Booking routes (attendee: create/cancel bookings)
    Route::post('/events/{event}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::delete('/events/{event}/cancel', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Waitlist routes (attendee only)
    Route::post('/events/{event}/waitlist/join', [WaitlistController::class, 'join'])->name('waitlists.join');
    Route::delete('/events/{event}/waitlist/leave', [WaitlistController::class, 'leave'])->name('waitlists.leave');
    Route::get('/my/waitlists', [WaitlistController::class, 'index'])->name('waitlists.index');

    // Dashboard (accessible by ALL authenticated users)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Organiser-only reporting page
    Route::get('/dashboard/report', [DashboardController::class, 'index'])
        ->middleware('organiser')
        ->name('dashboard.report');

    // User profile management (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';