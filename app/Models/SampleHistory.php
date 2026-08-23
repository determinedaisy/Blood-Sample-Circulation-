<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleHistory extends Model
{
    use HasFactory;

    // Defines which columns can be bulk-inserted
    protected $fillable = [
        'blood_sample_id',
        'user_id',
        'action',
        'description',
        'location'
    ];

    // Relationship: A history record belongs to one specific blood sample
    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class);
    }

    // Relationship: A history record was performed by one specific user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}