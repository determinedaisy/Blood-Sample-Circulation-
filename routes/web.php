<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BloodSampleReviewController;
use App\Http\Controllers\DonorRequestController;
use App\Http\Controllers\EmergencyPriorityController;
use App\Http\Controllers\EmergencySOSController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PatientBloodSampleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionRequestController;
use App\Http\Controllers\SampleHistoryController;
use App\Http\Controllers\SampleRequestController;
use App\Http\Controllers\SampleTransportationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/sample/card/{sample_code}', function ($sample_code) {
    $sample = \App\Models\BloodSample::with([
        'patient', 
        'sampleRequest', 
        'transportations'
    ])->where('sample_code', $sample_code)->firstOrFail();
    
    return view('sample-card', compact('sample'));
})->name('sample.card');

Route::get('/sample-history/{sample_code}', [SampleHistoryController::class, 'show'])->name('sample.history');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    // Admin Dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Emergency Priority Management
    Route::get('/admin/emergency-priority', [EmergencyPriorityController::class, 'index'])->name('admin.emergency-priority');
    Route::patch('/admin/emergency-priority/{emergencyRequest}', [EmergencyPriorityController::class, 'update'])->name('admin.emergency-priority.update');

    // Blood Sample Review
    Route::get('/blood-samples', [BloodSampleReviewController::class, 'index'])->name('blood-samples.index');
    Route::patch('/blood-samples/{bloodSample}/review', [BloodSampleReviewController::class, 'update'])->name('blood-samples.review');

    // Blood Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::patch('/inventory/{bloodSample}/collect', [InventoryController::class, 'collect'])->name('inventory.collect');

    // Patient Blood Samples
    Route::get('/my-blood-samples', [PatientBloodSampleController::class, 'index'])->name('patient.blood-samples.index');
    Route::get('/my-blood-samples/donate', [PatientBloodSampleController::class, 'create'])->name('patient.blood-samples.create');
    Route::post('/my-blood-samples/donate', [PatientBloodSampleController::class, 'store'])->name('patient.blood-samples.store');

    // Emergency SOS
    Route::get('/sos', [EmergencySOSController::class, 'index'])->name('sos.index');
    Route::post('/sos', [EmergencySOSController::class, 'store'])->name('sos.store');
    Route::get('/sos/results', [EmergencySOSController::class, 'results'])->name('sos.results');

    // Donor Request
    Route::post('/donor-request/{donor}', [DonorRequestController::class, 'store'])->name('donor.request');

    // Transportation
    Route::get('/transportation', [SampleTransportationController::class, 'index'])->name('transportation.index');
    Route::post('/transportation', [SampleTransportationController::class, 'store'])->name('transportation.store');
    Route::patch('/transportation/{transportation}/start', [SampleTransportationController::class, 'start'])->name('transportation.start');
    Route::patch('/transportation/{transportation}/deliver', [SampleTransportationController::class, 'deliver'])->name('transportation.deliver');

    // bKash Payment
    Route::get('/payment/bkash/initiate/{sample_code}', [App\Http\Controllers\PaymentController::class, 'initiate'])->name('payment.bkash.initiate');
    Route::get('/payment/bkash/callback', [App\Http\Controllers\PaymentController::class, 'callback'])->name('bkash.callback');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';