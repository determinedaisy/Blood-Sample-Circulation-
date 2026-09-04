<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleReportResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_name',
        'result_value',
        'unit',
        'reference_range',
        'flag',
        'notes',
        'sort_order',
    ];

    public function sampleReport()
    {
        return $this->belongsTo(SampleReport::class);
    }
}
