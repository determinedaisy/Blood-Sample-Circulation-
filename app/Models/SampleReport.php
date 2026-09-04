<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_sample_id',
        'doctor_id',
        'lab_submitted_by',
        'title',
        'doctor_notes',
        'patient_explanation',
        'status',
        'published_at',
        'lab_submitted_at',
        'attachment_path',
        'attachment_original_name',
        'ai_generated',
        'ai_error',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'lab_submitted_at' => 'datetime',
            'ai_generated' => 'boolean',
        ];
    }

    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function labSubmitter()
    {
        return $this->belongsTo(User::class, 'lab_submitted_by');
    }

    public function results()
    {
        return $this->hasMany(SampleReportResult::class)->orderBy('sort_order');
    }
}
