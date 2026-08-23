<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeCollectionRequest extends Model
{
    protected $fillable = [
        'sample_request_id',
        'patient_id',
        'assigned_collector_id',

        'address',
        'latitude',
        'longitude',

        'preferred_date',
        'preferred_time',

        'instructions',
        'status',

        'assigned_at',
        'on_the_way_at',
        'arrived_at',
        'collected_at',
    ];


    protected $casts = [
        'preferred_date' => 'date',

        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',

        'assigned_at' => 'datetime',
        'on_the_way_at' => 'datetime',
        'arrived_at' => 'datetime',
        'collected_at' => 'datetime',
    ];


    /**
     * Normal sample request this appointment belongs to.
     */
    public function sampleRequest()
    {
        return $this->belongsTo(
            SampleRequest::class
        );
    }


    /**
     * Patient requesting the home visit.
     */
    public function patient()
    {
        return $this->belongsTo(
            User::class,
            'patient_id'
        );
    }


    /**
     * Collector assigned to visit patient's home.
     */
    public function assignedCollector()
    {
        return $this->belongsTo(
            User::class,
            'assigned_collector_id'
        );
    }
}