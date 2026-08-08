<?php

use App\Http\Controllers\BloodSampleReviewController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get(
    '/blood-samples',
    [BloodSampleReviewController::class, 'index']
)->middleware('auth')->name('blood-samples.index');

Route::patch(
    '/blood-samples/{bloodSample}/review',
    [BloodSampleReviewController::class, 'update']
)->middleware('auth')->name('blood-samples.review');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';