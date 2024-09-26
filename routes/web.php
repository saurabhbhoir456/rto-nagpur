<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SidebarController;
use Illuminate\Support\Facades\Route;

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

Route::get('/vehicle-tax', [SidebarController::class, 'vehicleTax'])->name('vehicle.tax');
Route::get('/vehicle-permit', [SidebarController::class, 'vehiclePermit'])->name('vehicle.permit');
Route::get('/fitness-certificate', [SidebarController::class, 'fitnessCertificate'])->name('fitness.certificate');
Route::get('/driver-license', [SidebarController::class, 'driverLicense'])->name('driver.license');
require __DIR__.'/auth.php';
