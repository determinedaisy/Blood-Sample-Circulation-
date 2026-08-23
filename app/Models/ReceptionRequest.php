<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceptionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'sample_type',
        'blood_type',
        'notes',
        'status',
        'handled_by',
        'handled_at',
        'sample_request_id',
    ];

    protected function casts(): array
    {
        return [
            'handled_at' => 'datetime',
        ];
    }

    /**
     * Patient who contacted reception.
     */
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Receptionist who processed the request.
     */
    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    /**
     * Actual sample request created after reception processes it.
     */
    public function sampleRequest()
    {
        return $this->belongsTo(SampleRequest::class);
    }
}