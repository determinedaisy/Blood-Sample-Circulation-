<?php

use App\Http\Controllers\BloodSampleReviewController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PatientBloodSampleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Blood Sample Review
|--------------------------------------------------------------------------
*/

Route::get(
    '/blood-samples',
    [BloodSampleReviewController::class, 'index']
)->middleware('auth')->name('blood-samples.index');

Route::patch(
    '/blood-samples/{bloodSample}/review',
    [BloodSampleReviewController::class, 'update']
)->middleware('auth')->name('blood-samples.review');

/*
|--------------------------------------------------------------------------
| Blood Inventory
|--------------------------------------------------------------------------
*/

Route::get(
    '/inventory',
    [InventoryController::class, 'index']
)->middleware('auth')->name('inventory.index');

Route::patch(
    '/inventory/{bloodSample}/collect',
    [InventoryController::class, 'collect']
)->middleware('auth')->name('inventory.collect');

/*
|--------------------------------------------------------------------------
| Patient Blood Samples
|--------------------------------------------------------------------------
*/

Route::get(
    '/my-blood-samples',
    [PatientBloodSampleController::class, 'index']
)->middleware('auth')->name('patient.blood-samples.index');

Route::get(
    '/my-blood-samples/donate',
    [PatientBloodSampleController::class, 'create']
)->middleware('auth')->name('patient.blood-samples.create');

Route::post(
    '/my-blood-samples/donate',
    [PatientBloodSampleController::class, 'store']
)->middleware('auth')->name('patient.blood-samples.store');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});

require __DIR__.'/auth.php';