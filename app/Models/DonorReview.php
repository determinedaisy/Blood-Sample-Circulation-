<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonorReview extends Model
{

    protected $fillable = [

        'donor_id',
        'doctor_id',
        'medical_status',
        'doctor_note',
        'reviewed_at',

    ];



    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }



    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

}
