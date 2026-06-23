<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ContactMessageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin']);

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])->group(function () {

    Route::resource('doctors', DoctorController::class);

    Route::resource('services', ServiceController::class);

    Route::resource('schedules', DoctorScheduleController::class);

    Route::resource('appointments', AppointmentController::class);

    Route::resource('reviews', ReviewController::class);

    Route::resource('contact-messages', ContactMessageController::class);
});