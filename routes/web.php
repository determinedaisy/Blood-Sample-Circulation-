<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdministrativeReportController;
use App\Http\Controllers\BloodSampleReviewController;
use App\Http\Controllers\DonorRequestController;
use App\Http\Controllers\EmergencyPriorityController;
use App\Http\Controllers\EmergencySOSController;
use App\Http\Controllers\HomeCollectionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LaboratoryCapacityController;
use App\Http\Controllers\PatientBloodSampleController;
use App\Http\Controllers\PatientNotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionRequestController;
use App\Http\Controllers\SampleHistoryController;
use App\Http\Controllers\SampleRequestController;
use App\Http\Controllers\SampleReportController;
use App\Http\Controllers\SampleTransportationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::get('/sample/card/{sample_code}', function ($sample_code) {
    $sample = \App\Models\BloodSample::with([
        'patient',
        'sampleRequest',
        'transportations',
    ])->where('sample_code', $sample_code)->firstOrFail();

    return view('sample-card', compact('sample'));
})->name('sample.card');

Route::get('/sample-history/{sample_code}', [SampleHistoryController::class, 'show'])
    ->name('sample.history');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    })->middleware('verified')->name('dashboard');

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/admin/reports', [AdministrativeReportController::class, 'index'])
        ->name('admin.reports.index');
    Route::post('/admin/reports', [AdministrativeReportController::class, 'store'])
        ->name('admin.reports.store');
    Route::get('/admin/reports/{administrativeReport}', [AdministrativeReportController::class, 'show'])
        ->name('admin.reports.show');

    Route::get('/admin/emergency-priority', [EmergencyPriorityController::class, 'index'])
        ->name('admin.emergency-priority');
    Route::patch('/admin/emergency-priority/{emergencyRequest}', [EmergencyPriorityController::class, 'update'])
        ->name('admin.emergency-priority.update');

    Route::get('/sample-requests', [SampleRequestController::class, 'patientIndex'])
        ->name('sample-requests.patient.index');
    Route::get('/sample-requests/create', [SampleRequestController::class, 'create'])
        ->name('sample-requests.create');
    Route::post('/sample-requests', [SampleRequestController::class, 'store'])
        ->name('sample-requests.store');
    Route::get('/sample-requests/{sampleRequest}/tracking', [SampleRequestController::class, 'tracking'])
        ->name('sample-requests.tracking');

    Route::get('/sample-requests/{sampleRequest}/home-collection', [SampleRequestController::class, 'homeCollectionCreate'])
        ->name('sample-requests.home-collection.create');
    Route::post('/sample-requests/{sampleRequest}/home-collection', [SampleRequestController::class, 'homeCollectionStore'])
        ->name('sample-requests.home-collection.store');

    Route::get('/receptionist/sample-requests', [SampleRequestController::class, 'receptionistIndex'])
        ->name('sample-requests.receptionist.index');
    Route::get('/receptionist/sample-requests/create', [SampleRequestController::class, 'receptionistCreate'])
        ->name('sample-requests.receptionist.create');
    Route::post('/receptionist/sample-requests', [SampleRequestController::class, 'receptionistStore'])
        ->name('sample-requests.receptionist.store');

    Route::get('/admin/sample-requests', [SampleRequestController::class, 'adminIndex'])
        ->name('sample-requests.admin.index');
    Route::patch('/admin/sample-requests/{sampleRequest}/approve', [SampleRequestController::class, 'approve'])
        ->name('sample-requests.approve');
    Route::patch('/admin/sample-requests/{sampleRequest}/decline', [SampleRequestController::class, 'decline'])
        ->name('sample-requests.decline');
    Route::post('/admin/sample-requests/{sampleRequest}/assign-collector', [SampleRequestController::class, 'assignCollector'])
        ->name('sample-requests.assign-collector');
    Route::post('/admin/sample-requests/{sampleRequest}/assign-doctor', [SampleRequestController::class, 'assignDoctor'])
        ->name('sample-requests.assign-doctor');

    Route::get('/admin/home-collections', [HomeCollectionController::class, 'adminIndex'])
        ->name('home-collections.admin.index');
    Route::post('/admin/home-collections/route-order', [HomeCollectionController::class, 'updateRouteOrder'])
        ->name('home-collections.route-order');
    Route::post('/admin/home-collections/{homeCollection}/assign', [HomeCollectionController::class, 'assignCollector'])
        ->name('home-collections.assign');
    Route::post('/admin/home-collections/{homeCollection}/send-to-laboratory', [HomeCollectionController::class, 'sendToLaboratory'])
        ->name('home-collections.send-to-laboratory');

    Route::get('/laboratory-capacity', [LaboratoryCapacityController::class, 'index'])
        ->name('laboratory-capacity.index');
    Route::get('/laboratory-capacity/{laboratory}/workload', [LaboratoryCapacityController::class, 'workload'])
        ->name('laboratory-capacity.workload');
    Route::get('/laboratory-capacity/{laboratory}/samples/{sampleTransportation}/report', [SampleReportController::class, 'labEdit'])
        ->name('laboratory-capacity.report.edit');
    Route::put('/laboratory-capacity/{laboratory}/samples/{sampleTransportation}/report', [SampleReportController::class, 'labUpdate'])
        ->name('laboratory-capacity.report.update');
    Route::patch('/laboratory-capacity/{laboratory}', [LaboratoryCapacityController::class, 'update'])
        ->name('laboratory-capacity.update');

    Route::get('/collector/home-collections', [HomeCollectionController::class, 'collectorIndex'])
        ->name('home-collections.collector.index');
    Route::patch('/collector/home-collections/{homeCollection}/start', [HomeCollectionController::class, 'startTrip'])
        ->name('home-collections.collector.start');
    Route::patch('/collector/home-collections/{homeCollection}/arrive', [HomeCollectionController::class, 'markArrived'])
        ->name('home-collections.collector.arrive');
    Route::patch('/collector/home-collections/{homeCollection}/collect', [HomeCollectionController::class, 'markCollected'])
        ->name('home-collections.collector.collect');

    Route::get('/doctor/sample-requests', [SampleRequestController::class, 'doctorIndex'])
        ->name('sample-requests.doctor.index');
    Route::get('/doctor/sample-requests/{sampleRequest}/report', [SampleReportController::class, 'edit'])
        ->name('sample-reports.doctor.edit');
    Route::put('/doctor/sample-requests/{sampleRequest}/report', [SampleReportController::class, 'update'])
        ->name('sample-reports.doctor.update');
    Route::post('/doctor/sample-requests/{sampleRequest}/report/generate-ai', [SampleReportController::class, 'generateAi'])
        ->name('sample-reports.doctor.generate-ai');
    Route::post('/doctor/sample-requests/{sampleRequest}/report/publish', [SampleReportController::class, 'publish'])
        ->name('sample-reports.doctor.publish');
    Route::get('/doctor/sample-requests/{sampleRequest}', [SampleRequestController::class, 'doctorShow'])
        ->name('sample-requests.doctor.show');

    Route::get('/reception-requests', [ReceptionRequestController::class, 'patientIndex'])
        ->name('reception-requests.patient.index');
    Route::get('/reception-requests/create', [ReceptionRequestController::class, 'create'])
        ->name('reception-requests.create');
    Route::post('/reception-requests', [ReceptionRequestController::class, 'store'])
        ->name('reception-requests.store');
    Route::get('/receptionist/reception-requests', [ReceptionRequestController::class, 'receptionistIndex'])
        ->name('reception-requests.receptionist.index');
    Route::patch('/receptionist/reception-requests/{receptionRequest}/process', [ReceptionRequestController::class, 'process'])
        ->name('reception-requests.process');

    Route::get('/blood-samples', [BloodSampleReviewController::class, 'index'])
        ->name('blood-samples.index');
    Route::patch('/blood-samples/{bloodSample}/review', [BloodSampleReviewController::class, 'update'])
        ->name('blood-samples.review');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::patch('/inventory/{bloodSample}/collect', [InventoryController::class, 'collect'])
        ->name('inventory.collect');

    Route::get('/my-blood-samples', [PatientBloodSampleController::class, 'index'])
        ->name('patient.blood-samples.index');
    Route::get('/my-blood-samples/donate', [PatientBloodSampleController::class, 'create'])
        ->name('patient.blood-samples.create');
    Route::post('/my-blood-samples/donate', [PatientBloodSampleController::class, 'store'])
        ->name('patient.blood-samples.store');
    Route::get('/my-reports', [SampleReportController::class, 'patientIndex'])
        ->name('sample-reports.patient.index');
    Route::get('/my-notifications', [PatientNotificationController::class, 'index'])
        ->name('patient.notifications.index');
    Route::patch('/my-notifications/read-all', [PatientNotificationController::class, 'readAll'])
        ->name('patient.notifications.read-all');
    Route::patch('/my-notifications/{notification}/read', [PatientNotificationController::class, 'read'])
        ->name('patient.notifications.read');
    Route::get('/my-blood-samples/reports/{sampleReport}', [SampleReportController::class, 'patientShow'])
        ->name('sample-reports.patient.show');
    Route::get('/sample-reports/{sampleReport}/attachment', [SampleReportController::class, 'download'])
        ->name('sample-reports.download');

    Route::get('/sos', [EmergencySOSController::class, 'index'])->name('sos.index');
    Route::post('/sos', [EmergencySOSController::class, 'store'])->name('sos.store');
    Route::get('/sos/results', [EmergencySOSController::class, 'results'])->name('sos.results');
    Route::post('/donor-request/{donor}', [DonorRequestController::class, 'store'])
        ->name('donor.request');

    Route::get('/transportation', [SampleTransportationController::class, 'index'])
        ->name('transportation.index');
    Route::post('/transportation', [SampleTransportationController::class, 'store'])
        ->name('transportation.store');
    Route::patch('/transportation/{transportation}/start', [SampleTransportationController::class, 'start'])
        ->name('transportation.start');
    Route::patch('/transportation/{transportation}/deliver', [SampleTransportationController::class, 'deliver'])
        ->name('transportation.deliver');

    Route::get('/payment/bkash/initiate/{sample_code}', [\App\Http\Controllers\PaymentController::class, 'initiate'])
        ->name('payment.bkash.initiate');
    Route::get('/payment/bkash/callback', [\App\Http\Controllers\PaymentController::class, 'callback'])
        ->name('bkash.callback');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
