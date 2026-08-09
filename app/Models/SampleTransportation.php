<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleTransportation extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_sample_id',
        'collection_center_id',
        'laboratory_id',
        'transported_by',
        'status',
        'departure_time',
        'arrival_time',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_time' => 'datetime',
            'arrival_time' => 'datetime',
        ];
    }

    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class);
    }

    public function collectionCenter()
    {
        return $this->belongsTo(CollectionCenter::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function transporter()
    {
        return $this->belongsTo(User::class, 'transported_by');
    }
}
