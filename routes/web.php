<?php


use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\SampleTransportationController;
use App\Http\Controllers\BloodSampleReviewController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PatientBloodSampleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmergencySOSController;
use App\Http\Controllers\DonorRequestController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



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
| Admin Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/admin/dashboard',
    [AdminDashboardController::class, 'index']
)->middleware('auth')->name('admin.dashboard');





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


Route::get(
    '/inventory/create',
    [InventoryController::class, 'create']
)->middleware('auth')->name('inventory.create');


Route::post(
    '/inventory',
    [InventoryController::class, 'store']
)->middleware('auth')->name('inventory.store');


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
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {



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