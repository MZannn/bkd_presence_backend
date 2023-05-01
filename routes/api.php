<?php

use App\Http\Controllers\API\PresenceController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
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

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [UserController::class, 'fetch']);
        Route::get('presence', [PresenceController::class, 'all']);
        Route::post('user', [UserController::class, 'updateProfile']);
        Route::put('presence-in/{id}', [PresenceController::class, 'presenceIn']);
        Route::put('presence-out/{id}', [PresenceController::class, 'presenceOut']);
        Route::post('bussiness-trip', [PresenceController::class, 'bussinessTrip']);
        Route::post('permission-and-sick', [PresenceController::class, 'permissionAndSick']);
        Route::post('report-change-device', [UserController::class, 'reportChangeDevice']);
        Route::post('vacation', [PresenceController::class, 'vacation']);
        Route::get('detail-presence/{id}', [PresenceController::class, 'detailPresence']);
    });

Route::post('login', [AuthController::class, 'login']);
Route::get('unauthorized', [AuthController::class, 'unauthorized'])->name('unauthorized');