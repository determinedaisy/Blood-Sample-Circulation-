<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleRequest extends Model
{
    protected $fillable = [
        'patient_id',
        'requested_by',
        'blood_sample_id',
        'sample_type',
        'blood_type',
        'status',
        'approved_by',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}