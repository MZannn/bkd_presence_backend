<?php

use App\Http\Controllers\Admin\BussinessTripController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\PermissionAndSickController;
use App\Http\Controllers\Admin\ReportChangeDeviceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PresenceController;
use App\Http\Controllers\Admin\VacationController;
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
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('office', OfficeController::class);
        Route::resource('employee', EmployeeController::class);
        Route::resource('user', UserController::class);
        Route::get('/change-Password', [UserController::class, 'changePassword'])->name('changePassword');
        Route::post('/change-Password', [UserController::class, 'updatePasswordAdmin'])->name('changePassword.store');
        Route::get('/import-data-pegawai', [EmployeeController::class, 'toImport'])->name('employee.import');
        Route::post('/import-data-pegawai', [EmployeeController::class, 'import'])->name('employee.import.store');
        Route::resource('presence', PresenceController::class);
        Route::get('/bussiness-trip', [BussinessTripController::class, 'index'])->name('bussinessTrip');
        Route::get('/bussiness-trip/{id}', [BussinessTripController::class, 'edit'])->name('bussinessTrip.edit');
        Route::post('/bussiness-trip', [BussinessTripController::class, 'validation'])->name('bussinessTrip.validation');
        Route::get('/permission-and-sick', [PermissionAndSickController::class, 'index'])->name('permissionAndSick');
        Route::post('/permission-and-sick', [PermissionAndSickController::class, 'validation'])->name('permissionAndSick.validation');
        Route::get('/permission-and-sick/{id}', [PermissionAndSickController::class, 'edit'])->name('permissionAndSick.edit');
        Route::get('/report-change-device', [ReportChangeDeviceController::class, 'index'])->name('reportChangeDevice');
        Route::post('/report-change-device', [ReportChangeDeviceController::class, 'approved'])->name('reportChangeDevice.approved');
        Route::post('/export-presence', [PresenceController::class, 'export'])->name('presence.export');
        Route::get('/vacation', [VacationController::class, 'index'])->name('vacation');
        Route::post('/vacation', [VacationController::class, 'validation'])->name('vacation.validation');
        Route::get('/insert-template', [EmployeeController::class, 'insertTemplate'])->name('employee.insertTemplate');
        Route::post('/insert-template', [EmployeeController::class, 'storeTemplate'])->name('employee.storeTemplate');
        Route::get('/change-template', [EmployeeController::class, 'editTemplate'])->name('employee.changeTemplate');
        Route::post('/change-template', [EmployeeController::class, 'updateTemplate'])->name('employee.updateTemplate');

    });
Auth::routes(['register' => false]);