<?php 
 
namespace App\Http\Controllers; 
 
use App\Http\Requests\ReviewBloodSampleRequest; 
use App\Models\BloodSample; 
use App\Notifications\BloodSampleAcceptedNotification; 
use App\Notifications\BloodSampleRejectedNotification; 
use Illuminate\Http\RedirectResponse; 
use Illuminate\Support\Facades\DB; 
use App\Notifications\BloodSampleAcceptedPatientNotification; 
 
class BloodSampleReviewController extends Controller 
{ 
    /** 
     * Show blood samples.
     * 
     * Admin:
     * - Can see ALL blood sample history.
     * 
     * Lab Staff:
     * - Can see only blood samples assigned to them.
     */ 
    public function index() 
    { 
        if (!auth()->check()) { 
            abort(403); 
        } 
 
        $user = auth()->user(); 
 
        if ($user->role === 'admin') { 
 
            // Admin can see complete blood sample history.
            $bloodSamples = BloodSample::with([ 
                'patient', 
                'donor', 
                'collector', 
                'reviewer', 
                'assignedLabStaff', 
                'transportations.transporter', 
                'transportations.collectionCenter', 
                'transportations.laboratory', 
            ]) 
                ->orderByDesc('created_at') 
                ->get(); 
 
        } elseif ($user->role === 'lab_staff') { 
 
            // Lab Staff can see only samples assigned to them.
            $bloodSamples = BloodSample::with([ 
                'patient', 
                'donor', 
                'collector', 
                'reviewer', 
                'assignedLabStaff', 
                'transportations.transporter', 
                'transportations.collectionCenter', 
                'transportations.laboratory', 
            ]) 
                ->where('assigned_lab_staff_id', $user->id) 
                ->orderByDesc('created_at') 
                ->get(); 
 
        } else { 
 
            abort(403); 
        } 
 
        return view( 
            'blood-samples.index', 
            compact('bloodSamples') 
        ); 
    } 
 
    /** 
     * Accept or reject a blood sample. 
     */ 
    public function update( 
        ReviewBloodSampleRequest $request, 
        BloodSample $bloodSample 
    ): RedirectResponse { 
 
        /* 
        |-------------------------------------------------------------------------- 
        | Security: only the assigned Lab Staff can review this sample 
        |-------------------------------------------------------------------------- 
        */ 
 
        if ( 
            !auth()->check() 
            || auth()->user()->role !== 'lab_staff' 
            || $bloodSample->assigned_lab_staff_id !== auth()->id() 
        ) { 
            abort(403); 
        } 
 
        $data = $request->validated(); 
 
        DB::transaction(function () use ($data, $bloodSample) { 
 
            /* 
            |-------------------------------------------------------------------------- 
            | Update Blood Sample Review 
            |-------------------------------------------------------------------------- 
            */ 
 
            $bloodSample->update([ 
                'status' => $data['decision'], 
 
                'quality_checks' => 
                    $data['quality_checks'] ?? null, 
 
                'rejection_reason' => 
                    $data['decision'] === 'rejected' 
                        ? $data['rejection_reason'] 
                        : null, 
 
                'reviewed_by' => auth()->id(), 
 
                'reviewed_at' => now(), 
            ]); 
 
            /* 
            |-------------------------------------------------------------------------- 
            | Rejected Donation 
            |-------------------------------------------------------------------------- 
            */ 
 
            if ($data['decision'] === 'rejected') { 
 
                $bloodSample->load('patient'); 
 
                if ($bloodSample->patient) { 
 
                    $bloodSample->patient->notify( 
                        new BloodSampleRejectedNotification($bloodSample) 
                    ); 
                } 
 
                return; 
            } 
 
            /* 
            |-------------------------------------------------------------------------- 
            | Accepted Donation - Feature 20 
            |-------------------------------------------------------------------------- 
            */ 
 
            if ($data['decision'] === 'accepted') { 
 
                $bloodSample->load([ 
                    'patient', 
                    'donor', 
                ]); 
 
                /* 
                |------------------------------------------------------------------ 
                | Notify Patient 
                |------------------------------------------------------------------ 
                */ 
 
                if ($bloodSample->patient) { 
 
                    $bloodSample->patient->notify( 
                        new BloodSampleAcceptedPatientNotification($bloodSample) 
                    ); 
                } 
 
                /* 
                |------------------------------------------------------------------ 
                | Update Donor Badge + Notify Donor 
                |------------------------------------------------------------------ 
                */ 
 
                if ($bloodSample->donor) { 
 
                    $bloodSample->donor->updateBadge(); 
 
                    $bloodSample->donor->refresh(); 
 
                    $bloodSample->donor->user?->notify( 
                        new BloodSampleAcceptedNotification($bloodSample) 
                    ); 
                } 
            } 
        }); 
 
        /* 
        |-------------------------------------------------------------------------- 
        | Response Messages 
        |-------------------------------------------------------------------------- 
        */ 
 
        if ($data['decision'] === 'rejected') { 
 
            return back()->with( 
                'warning', 
                'Blood sample rejected successfully. The patient has been notified.' 
            ); 
        } 
 
        return back()->with( 
            'success', 
            'Blood sample accepted successfully. Donor badge has been updated and the donor has been notified.' 
        ); 
    } 
}