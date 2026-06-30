<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContactMessageController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\DoctorScheduleController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ServiceController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact-messages', [ContactMessageController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResources([
        'doctors' => DoctorController::class,
        'services' => ServiceController::class,
        'appointments' => AppointmentController::class,
        'reviews' => ReviewController::class,
        'schedules' => DoctorScheduleController::class,
    ]);

    Route::apiResource('contact-messages', ContactMessageController::class)->except(['store']);
});