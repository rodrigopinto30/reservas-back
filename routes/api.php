<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

Route::post('/register', [Controller::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
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
    'middleware' => 'api',
    'prefix' => 'reservation'
], function () {
    Route::get('/', [ReservationController::class, 'index'])->name('reservation.index');
    Route::get('/{id}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::post('/', [ReservationController::class, 'store'])->name('reservation.store');
    Route::put('/', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'space'
], function () {
    Route::get('/', [SpaceController::class, 'index'])->name('space.index');
    Route::get('/{id}', [SpaceController::class, 'show'])->name('space.show');
    Route::post('/', [SpaceController::class, 'store'])->name('space.store');
    Route::put('/', [SpaceController::class, 'update'])->name('space.update');
    Route::delete('/{id}', [SpaceController::class, 'destroy'])->name('space.destroy');
});
