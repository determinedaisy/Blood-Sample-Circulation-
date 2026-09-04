<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'generated_by',
        'report_type',
        'start_date',
        'end_date',
        'metrics',
        'ai_summary',
        'ai_generated',
        'ai_error',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'metrics' => 'array',
            'ai_generated' => 'boolean',
        ];
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
