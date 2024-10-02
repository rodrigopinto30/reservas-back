<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/register', [Controller::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});
Route::group([
    'prefix' => 'user'
], function(){
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/{id}', [UserController::class, 'show'])->name('user.show');
});

Route::group([
    'prefix' => 'reservation'
], function(){
    Route::get('/', [ReservationController::class, 'index'])->name('reservation.index');
    Route::get('/{id}', [ReservationController::class, 'show'])->name('reservation.show');
    Route::post('/', [ReservationController::class, 'store'])->name('reservation.store');
    Route::put('/', [ReservationController::class, 'update'])->name('reservation.update');
    Route::delete('/{id}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
});

Route::group([
    'prefix' => 'space'
], function(){
    Route::get('/', [SpaceController::class, 'index'])->name('space.index');
    Route::get('/{id}', [SpaceController::class, 'show'])->name('space.show');
    Route::post('/', [SpaceController::class, 'store'])->name('space.store');
    Route::put('/', [SpaceController::class, 'update'])->name('space.update');
    Route::delete('/{id}', [SpaceController::class, 'destroy'])->name('space.destroy');
});