<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_code',
        'patient_id',
        'collected_by',
        'sample_type',
        'status',
        'collected_at',
        'quality_checks',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'quality_checks' => 'array',
            'collected_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}