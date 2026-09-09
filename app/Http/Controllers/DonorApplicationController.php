<?php

namespace App\Http\Controllers;

use App\Models\Donor;
use App\Models\DonorApplication;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonorApplicationController extends Controller
{
    /**
     * Make sure the logged-in user is a patient
     * and return their patient profile.
     */
    private function ensurePatient(): Patient
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'patient',
            403
        );

        return Patient::where(
            'user_id',
            Auth::id()
        )->firstOrFail();
    }

    /**
     * Make sure the logged-in user is a doctor.
     */
    private function ensureDoctor(): User
    {
        abort_unless(
            Auth::check() && Auth::user()->role === 'doctor',
            403
        );

        return Auth::user();
    }

    /**
     * Show the donor/blood request form.
     */
    public function create()
    {
        $patient = $this->ensurePatient();

        return view(
            'donor-applications.create',
            compact('patient')
        );
    }

    /**
     * Store a new donor/blood request.
     */
    public function store(Request $request)
    {
        $patient = $this->ensurePatient();

        $validated = $request->validate([
            'request_type' => [
                'required',
                'in:donate,request',
            ],

            'blood_group' => [
                'required',
                'string',
                'max:10',
            ],

            'age' => [
                'nullable',
                'integer',
                'min:16',
                'max:100',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:30',
            ],

            'height' => [
                'nullable',
                'numeric',
                'min:50',
                'max:250',
            ],

            'weight' => [
                'nullable',
                'numeric',
                'min:20',
                'max:300',
            ],

            'blood_pressure_systolic' => [
                'nullable',
                'integer',
                'min:50',
                'max:250',
            ],

            'blood_pressure_diastolic' => [
                'nullable',
                'integer',
                'min:30',
                'max:150',
            ],

            'hemoglobin' => [
                'nullable',
                'numeric',
                'min:1',
                'max:30',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'previous_donation_date' => [
                'nullable',
                'date',
            ],

            'previous_donation_count' => [
                'nullable',
                'integer',
                'min:0',
                'max:1000',
            ],

            'medical_conditions' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'current_medications' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'recent_illness_or_surgery' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'smoking_status' => [
                'nullable',
                'string',
                'max:50',
            ],

            'pregnancy_status' => [
                'nullable',
                'string',
                'max:50',
            ],

            'allergies' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'additional_medical_information' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'requested_units' => [
                'nullable',
                'integer',
                'min:1',
                'max:20',
            ],

            'urgency' => [
                'nullable',
                'string',
                'max:50',
            ],

            'request_reason' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'hospital_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'hospital_location' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'required_date' => [
                'nullable',
                'date',
            ],
        ]);

        /*
         * Use information already stored in the patient's
         * profile when the form did not provide it.
         */
        $validated['phone'] =
            $validated['phone'] ?? $patient->phone;

        $validated['gender'] =
            $validated['gender'] ?? $patient->gender;

        $validated['blood_group'] =
            $validated['blood_group'] ?? $patient->blood_group;

        /*
         * Calculate age from date of birth when age was
         * not supplied by the form.
         */
        if (
            empty($validated['age']) &&
            $patient->date_of_birth
        ) {
            $validated['age'] =
                $patient->date_of_birth->age;
        }

        /*
         * donor_applications.patient_id references users.id,
         * not patients.id.
         */
        $validated['patient_id'] = $patient->user_id;

        $validated['status'] = 'pending';

        DonorApplication::create($validated);

        return redirect()
            ->route('donor-applications.index')
            ->with(
                'success',
                'Your application has been submitted to a doctor for review.'
            );
    }

    /**
     * Show the logged-in patient's applications.
     */
    public function index()
    {
        $patient = $this->ensurePatient();

        /*
         * donor_applications.patient_id stores the users.id.
         */
        $applications = DonorApplication::with('doctor')
            ->where(
                'patient_id',
                $patient->user_id
            )
            ->latest()
            ->get();

        return view(
            'donor-applications.index',
            compact('applications')
        );
    }

    /**
     * Show one application to the patient.
     */
    public function show(
        DonorApplication $donorApplication
    ) {
        $patient = $this->ensurePatient();

        /*
         * The application belongs to the User record.
         */
        abort_unless(
            $donorApplication->patient_id === $patient->user_id,
            403
        );

        $donorApplication->load('doctor');

        return view(
            'donor-applications.show',
            compact('donorApplication')
        );
    }

    /**
     * Doctor dashboard.
     */
    public function doctorIndex()
    {
        $this->ensureDoctor();

        $applications = DonorApplication::with([
            'patient',
            'doctor',
        ])
            ->latest()
            ->get();

        return view(
            'doctor.donor-applications.index',
            compact('applications')
        );
    }

    /**
     * Doctor views one application.
     */
    public function doctorShow(
        DonorApplication $donorApplication
    ) {
        $this->ensureDoctor();

        $donorApplication->load([
            'patient',
            'doctor',
        ]);

        return view(
            'doctor.donor-applications.show',
            compact('donorApplication')
        );
    }

    /**
     * Doctor approves an application.
     */
    public function approve(
        Request $request,
        DonorApplication $donorApplication
    ) {
        $doctor = $this->ensureDoctor();

        $validated = $request->validate([
            'doctor_note' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        DB::transaction(function () use (
            $donorApplication,
            $doctor,
            $validated
        ) {
            $donorApplication->update([
                'status' => 'approved',
                'doctor_id' => $doctor->id,
                'doctor_note' =>
                    $validated['doctor_note'] ?? null,
                'reviewed_at' => now(),
            ]);

            /*
             * If the patient applied to donate blood,
             * create or update their donor profile.
             */
            if (
                $donorApplication->request_type === 'donate'
            ) {
                $patientUser =
                    $donorApplication->patient;

                $phone =
                    $donorApplication->phone;

                if (
                    empty($phone) &&
                    $patientUser &&
                    $patientUser->patientProfile
                ) {
                    $phone =
                        $patientUser
                            ->patientProfile
                            ->phone;
                }

                Donor::updateOrCreate(
                    [
                        'user_id' =>
                            $donorApplication->patient_id,
                    ],
                    [
                        'blood_group' =>
                            $donorApplication->blood_group,

                        'phone' =>
                            $phone ?? '',

                        'is_willing' => true,

                        'is_available' => true,

                        'is_verified' => true,
                    ]
                );
            }
        });

        return redirect()
            ->route(
                'donor-applications.doctor.show',
                $donorApplication
            )
            ->with(
                'success',
                'Application approved successfully.'
            );
    }

    /**
     * Doctor rejects an application.
     */
    public function reject(
        Request $request,
        DonorApplication $donorApplication
    ) {
        $doctor = $this->ensureDoctor();

        $validated = $request->validate([
            'doctor_note' => [
                'required',
                'string',
                'max:5000',
            ],
        ]);

        $donorApplication->update([
            'status' => 'rejected',
            'doctor_id' => $doctor->id,
            'doctor_note' =>
                $validated['doctor_note'],
            'reviewed_at' => now(),
        ]);

        return redirect()
            ->route(
                'donor-applications.doctor.show',
                $donorApplication
            )
            ->with(
                'success',
                'Application rejected.'
            );
    }
}

