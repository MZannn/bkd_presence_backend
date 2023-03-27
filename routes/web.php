<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PresenceController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('/')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('office', OfficeController::class);
        Route::resource('employee', EmployeeController::class);
        Route::get('/change-Password', [UserController::class, 'index'])->name('changePassword');
        Route::post('/change-Password', [UserController::class, 'updatePasswordAdmin'])->name('changePassword.store');
        Route::get('/import-data-pegawai', [EmployeeController::class, 'toImport'])->name('employee.import');
        Route::post('/import-data-pegawai', [EmployeeController::class, 'import'])->name('employee.import.store');
        Route::get('/presensi', [PresenceController::class, 'index'])->name('presence.index');
    });
Auth::routes();

Route::get('/welcome', function () {
    return view('welcome');
});