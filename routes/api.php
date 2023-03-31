<?php

use App\Http\Controllers\API\PresenceController;
use App\Http\Controllers\API\AuthController;
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

Route::middleware(['auth:sanctum,api'])->group(function () {
    Route::get('presence', [PresenceController::class, 'all']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'fetch']);
    Route::post('user', [AuthController::class, 'updateProfile']);
    Route::post('user/photo', [AuthController::class, 'updatePhoto']);
    Route::put('presence-in/{id}', [PresenceController::class, 'presenceIn']);
    Route::put('presence-out/{id}', [PresenceController::class, 'presenceOut']);
    Route::get('presence-all', [PresenceController::class, 'all']);
});

Route::post('login', [AuthController::class, 'login']);
Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');