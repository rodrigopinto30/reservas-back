<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Support\Facades\Route;


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('/register', [Controller::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/me', [AuthController::class, 'me']);
});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'user'
], function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
});


Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'reservation'
], function () {
    Route::post('/', [ReservationController::class, 'store'])->name('reservation.store');
    Route::get('/active', [ReservationController::class, 'activeReservations'])->name('reservations.active');
    Route::get('/finished', [ReservationController::class, 'finishedReservations'])->name('reservations.finished');
    Route::get('/', [ReservationController::class, 'index'])->name('reservation.index');
    Route::get('/{id}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::put('/', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
});

Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'space'
], function () {
    Route::get('/', [SpaceController::class, 'index'])->name('space.index');
    Route::get('/{id}', [SpaceController::class, 'show'])->name('space.show');
    Route::post('/', [SpaceController::class, 'store'])->name('space.store');
    Route::put('/', [SpaceController::class, 'update'])->name('space.update');
    Route::delete('/{id}', [SpaceController::class, 'destroy'])->name('space.destroy');
});
