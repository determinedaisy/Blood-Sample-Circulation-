<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}