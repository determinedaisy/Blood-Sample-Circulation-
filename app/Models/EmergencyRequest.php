<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmergencyRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'blood_group',
        'latitude',
        'longitude',
        'status',
        'priority',
        'priority_reason',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(
            Patient::class
        );
    }

    public function donorRequests(): HasMany
    {
        return $this->hasMany(
            DonorRequest::class
        );
    }
}