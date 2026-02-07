<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::group(['middleware' => 'auth:api'], function() {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::group(['middleware' => 'auth:api'], function () {
    // Owner Routes
    Route::post('owner/motors', [MotorController::class, 'store']);
    Route::get('owner/motors', [MotorController::class, 'ownerMotors']);
    Route::get('owner/motors/{id}', [MotorController::class, 'show']);
    Route::put('owner/motors/{id}', [MotorController::class, 'update']);
    Route::delete('owner/motors/{id}', [MotorController::class, 'destroy']);
    Route::get('owner/revenue', [BookingController::class, 'ownerRevenue']);
    
    // Renter Routes
    Route::get('motors', [MotorController::class, 'index']); // Public/All
    Route::post('bookings', [BookingController::class, 'store']);
    Route::post('payments', [BookingController::class, 'pay']);
    Route::get('bookings/history', [BookingController::class, 'history']);
    
    // Admin Routes
    Route::get('admin/motors/pending', [AdminController::class, 'pendingMotors']);
    Route::patch('admin/motors/{id}/verify', [AdminController::class, 'verifyMotor']);
    Route::patch('admin/bookings/{id}/confirm', [AdminController::class, 'confirmBooking']);
    Route::get('admin/reports/revenue', [AdminController::class, 'revenueReport']);
});
