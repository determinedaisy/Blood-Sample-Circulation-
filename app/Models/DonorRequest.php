<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonorRequest extends Model
{
    protected $fillable = [
        'emergency_request_id',
        'patient_id',
        'donor_id',
        'status',
        'patient_latitude',
        'patient_longitude',
        'donor_latitude',
        'donor_longitude',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function emergencyRequest(): BelongsTo
    {
        return $this->belongsTo(
            EmergencyRequest::class
        );
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class
        );
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(
            Donor::class
        );
    }
}