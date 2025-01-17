<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\VehicleTaxController;
use App\Http\Controllers\VehicleFitnessController;
use App\Http\Controllers\EchallanController;
use App\Http\Controllers\VehiclePermitController;
use App\Http\Controllers\EnvironmentTaxController;
use App\Http\Controllers\DrivingLicenseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SMSLogsController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


Route::get('/', function () {
    return view('welcome');
});
Route::get('/sms-logs', [SMSLogsController::class, 'index_get'])->name('sms-logs.index');
Route::post('/sms-logs', [SMSLogsController::class, 'index_post'])->name('sms-logs.index');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/vehicle-tax', [VehicleTaxController::class, 'index'])->name('vehicle-tax.index');
    Route::get('/vehicle-tax/filter', [VehicleTaxController::class, 'filterVehicleTaxes'])->name('vehicle-tax.filter');

    Route::post('/vehicle-tax/upload', [VehicleTaxController::class, 'upload'])->name('vehicle-tax.upload');
    Route::get('/vehicle-tax/create', [VehicleTaxController::class, 'create'])->name('vehicle-tax.create');
    Route::post('/vehicle-tax', [VehicleTaxController::class, 'store'])->name('vehicle-tax.store');
    Route::get('/vehicle-tax/{id}/edit', [VehicleTaxController::class, 'edit'])->name('vehicle-tax.edit');
    Route::put('/vehicle-tax/{id}', [VehicleTaxController::class, 'update'])->name('vehicle-tax.update');
    Route::delete('/vehicle-tax/{id}', [VehicleTaxController::class, 'destroy'])->name('vehicle-tax.destroy');
    Route::post('/vehicle-tax/delete-selected', [VehicleTaxController::class, 'destroyMultiple'])->name('vehicle-tax.destroyMultiple');
    Route::post('/vehicle-tax/send-sms', [VehicleTaxController::class, 'sendSms'])->name('vehicle-tax.sendSms');
    Route::get('/vehicle-tax-logs', [VehicleTaxController::class, 'logs'])->name('vehicle-tax.logs');
    Route::post('/vehicle-fitness/upload', [VehicleFitnessController::class, 'uploadCsv'])->name('vehicle-fitness.upload');
    Route::post('/vehicle-fitness/sendSms', [VehicleFitnessController::class, 'sendSms'])->name('vehicle-fitness.sendSms');
    Route::delete('/vehicle-fitness', [VehicleFitnessController::class, 'deleteVehicleFitnesses'])->name('vehicle-fitness.delete');
    Route::get('/vehicle-fitness', [VehicleFitnessController::class, 'index'])->name('vehicle-fitness.index');
    Route::get('/vehicle-fitness-logs', [VehicleFitnessController::class, 'logs'])->name('vehicle-fitness.logs');
    Route::get('/vehicle-permits', [VehiclePermitController::class, 'index'])->name('vehicle-permits.index');
    Route::post('/vehicle-permits/upload', [VehiclePermitController::class, 'uploadCsv'])->name('vehicle-permits.upload');
    Route::delete('/vehicle-permits/delete', [VehiclePermitController::class, 'deleteVehiclePermits'])->name('vehicle-permits.delete');
    Route::post('/vehicle-permits/send-sms', [VehiclePermitController::class, 'sendSms'])->name('vehicle-permits.sendSms');
    Route::get('/vehicle-permits/logs', [VehiclePermitController::class, 'logs'])->name('vehicle-permit-logs.index');

    Route::get('/echallan', action: [EchallanController::class, 'index'])->name('echallan.index');
    Route::get('/echallans', [EchallanController::class, 'index']);
    Route::post('/echallans/upload', [EchallanController::class, 'uploadCsv'])->name('echallans.upload');   
    Route::delete('/echallans', [EchallanController::class, 'deleteEchallans'])->name('echallans.delete');
    Route::post('/echallans/sendSms', [EchallanController::class, 'sendSms'])->name('echallans.sendSms');
    Route::resource('vehicle-permit', VehiclePermitController::class);
    Route::resource('environment-tax', EnvironmentTaxController::class);
    Route::post('/environment-tax/upload', [EnvironmentTaxController::class, 'uploadCsv'])->name('environment-tax.upload');
    Route::delete('/environment-tax', [EnvironmentTaxController::class, 'deleteEnvironmentTaxes'])->name('environment-tax.delete');
    Route::post('/environment-tax/sendSms', [EnvironmentTaxController::class, 'sendSms'])->name('environment-tax.sendSms');
    Route::get('/environment-tax-logs', [EnvironmentTaxController::class, 'logs'])->name('environment-tax-logs.index');
    Route::get('/driving-licenses', [DrivingLicenseController::class, 'index'])->name('driving-licenses.index');
    Route::post('/driving-licenses/upload', [DrivingLicenseController::class, 'uploadCsv'])->name('driving-licenses.upload');
    Route::delete('/driving-licenses', [DrivingLicenseController::class, 'deleteDrivingLicenses'])->name('driving-licenses.delete');
    Route::post('/driving-licenses/sendSms', [DrivingLicenseController::class, 'sendSms'])->name('driving-licenses.sendSms');
    Route::get('/driving-license-logs', [DrivingLicenseController::class, 'logs'])->name('driving-license-logs.index');
    Route::get('/echallan-logs', [EchallanController::class, 'logs'])->name('echallan-logs.index');
});
require __DIR__.'/auth.php';
