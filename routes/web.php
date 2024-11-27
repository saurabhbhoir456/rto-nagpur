<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SidebarController;
use App\Http\Controllers\VehicleTaxController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
});


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
    // Route::get('/vehicle-tax', [SidebarController::class, 'vehicleTax'])->name('vehicle.tax');
    // Route::get('/vehicle-permit', [SidebarController::class, 'vehiclePermit'])->name('vehicle.permit');
    // Route::get('/fitness-certificate', [SidebarController::class, 'fitnessCertificate'])->name('fitness.certificate');
    // Route::get('/driver-license', [SidebarController::class, 'driverLicense'])->name('driver.license');
});
require __DIR__.'/auth.php';
