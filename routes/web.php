<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BloodSampleReviewController;
use App\Http\Controllers\DonorRequestController;
use App\Http\Controllers\EmergencySOSController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PatientBloodSampleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionRequestController;
use App\Http\Controllers\SampleRequestController;
use App\Http\Controllers\SampleTransportationController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Welcome
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return view('dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Patient Sample Requests
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/sample-requests',
        [SampleRequestController::class, 'patientIndex']
    )->name('sample-requests.patient.index');

    Route::get(
        '/sample-requests/create',
        [SampleRequestController::class, 'create']
    )->name('sample-requests.create');

    Route::post(
        '/sample-requests',
        [SampleRequestController::class, 'store']
    )->name('sample-requests.store');

    Route::get(
        '/sample-requests/{sampleRequest}/tracking',
        [SampleRequestController::class, 'tracking']
    )->name('sample-requests.tracking');


    /*
    |--------------------------------------------------------------------------
    | Receptionist Sample Requests
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/receptionist/sample-requests',
        [SampleRequestController::class, 'receptionistIndex']
    )->name('sample-requests.receptionist.index');

    Route::get(
        '/receptionist/sample-requests/create',
        [SampleRequestController::class, 'receptionistCreate']
    )->name('sample-requests.receptionist.create');

    Route::post(
        '/receptionist/sample-requests',
        [SampleRequestController::class, 'receptionistStore']
    )->name('sample-requests.receptionist.store');


    /*
    |--------------------------------------------------------------------------
    | Admin Sample Requests
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/admin/sample-requests',
        [SampleRequestController::class, 'adminIndex']
    )->name('sample-requests.admin.index');

    Route::patch(
        '/admin/sample-requests/{sampleRequest}/approve',
        [SampleRequestController::class, 'approve']
    )->name('sample-requests.approve');

    Route::patch(
        '/admin/sample-requests/{sampleRequest}/decline',
        [SampleRequestController::class, 'decline']
    )->name('sample-requests.decline');

    Route::post(
        '/admin/sample-requests/{sampleRequest}/assign-collector',
        [SampleRequestController::class, 'assignCollector']
    )->name('sample-requests.assign-collector');


    /*
    |--------------------------------------------------------------------------
    | Contact Receptionist
    |--------------------------------------------------------------------------
    */

    // Patient
    Route::get(
        '/reception-requests',
        [ReceptionRequestController::class, 'patientIndex']
    )->name('reception-requests.patient.index');

    Route::get(
        '/reception-requests/create',
        [ReceptionRequestController::class, 'create']
    )->name('reception-requests.create');

    Route::post(
        '/reception-requests',
        [ReceptionRequestController::class, 'store']
    )->name('reception-requests.store');


    // Receptionist
    Route::get(
        '/receptionist/reception-requests',
        [ReceptionRequestController::class, 'receptionistIndex']
    )->name('reception-requests.receptionist.index');

    Route::patch(
        '/receptionist/reception-requests/{receptionRequest}/process',
        [ReceptionRequestController::class, 'process']
    )->name('reception-requests.process');


    /*
    |--------------------------------------------------------------------------
    | Blood Sample Review
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/blood-samples',
        [BloodSampleReviewController::class, 'index']
    )->name('blood-samples.index');

    Route::patch(
        '/blood-samples/{bloodSample}/review',
        [BloodSampleReviewController::class, 'update']
    )->name('blood-samples.review');


    /*
    |--------------------------------------------------------------------------
    | Blood Inventory
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/inventory',
        [InventoryController::class, 'index']
    )->name('inventory.index');

    Route::get(
        '/inventory/create',
        [InventoryController::class, 'create']
    )->name('inventory.create');

    Route::post(
        '/inventory',
        [InventoryController::class, 'store']
    )->name('inventory.store');

    Route::patch(
        '/inventory/{bloodSample}/collect',
        [InventoryController::class, 'collect']
    )->name('inventory.collect');


    /*
    |--------------------------------------------------------------------------
    | Patient Blood Samples
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/my-blood-samples',
        [PatientBloodSampleController::class, 'index']
    )->name('patient.blood-samples.index');

    Route::get(
        '/my-blood-samples/donate',
        [PatientBloodSampleController::class, 'create']
    )->name('patient.blood-samples.create');

    Route::post(
        '/my-blood-samples/donate',
        [PatientBloodSampleController::class, 'store']
    )->name('patient.blood-samples.store');


    /*
    |--------------------------------------------------------------------------
    | Emergency SOS
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/sos',
        [EmergencySOSController::class, 'index']
    )->name('sos.index');

    Route::post(
        '/sos',
        [EmergencySOSController::class, 'store']
    )->name('sos.store');

    Route::get(
        '/sos/results',
        [EmergencySOSController::class, 'results']
    )->name('sos.results');


    /*
    |--------------------------------------------------------------------------
    | Donor Request
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/donor-request/{donor}',
        [DonorRequestController::class, 'store']
    )->name('donor.request');


    /*
    |--------------------------------------------------------------------------
    | Transportation
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/transportation',
        [SampleTransportationController::class, 'index']
    )->name('transportation.index');

    Route::post(
        '/transportation',
        [SampleTransportationController::class, 'store']
    )->name('transportation.store');

    Route::patch(
        '/transportation/{transportation}/start',
        [SampleTransportationController::class, 'start']
    )->name('transportation.start');

    Route::patch(
        '/transportation/{transportation}/deliver',
        [SampleTransportationController::class, 'deliver']
    )->name('transportation.deliver');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

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