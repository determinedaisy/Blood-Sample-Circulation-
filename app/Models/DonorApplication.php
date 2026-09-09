<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorApplication extends Model
{
    protected $fillable = [
        'patient_id',
        'request_type',
        'blood_group',
        'age',
        'gender',
        'height',
        'weight',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'hemoglobin',
        'phone',
        'previous_donation_date',
        'previous_donation_count',
        'medical_conditions',
        'current_medications',
        'recent_illness_or_surgery',
        'smoking_status',
        'pregnancy_status',
        'allergies',
        'additional_medical_information',
        'requested_units',
        'urgency',
        'request_reason',
        'hospital_name',
        'hospital_location',
        'required_date',
        'status',
        'doctor_id',
        'doctor_note',
        'reviewed_at',
    ];

    protected $casts = [
        'age' => 'integer',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'blood_pressure_systolic' => 'integer',
        'blood_pressure_diastolic' => 'integer',
        'hemoglobin' => 'decimal:1',
        'previous_donation_date' => 'date',
        'previous_donation_count' => 'integer',
        'requested_units' => 'integer',
        'required_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Patient
    |--------------------------------------------------------------------------
    */

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Doctor
    |--------------------------------------------------------------------------
    */

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isDonationRequest(): bool
    {
        return $this->request_type === 'donate';
    }

    public function isBloodRequest(): bool
    {
        return $this->request_type === 'request';
    }
}

