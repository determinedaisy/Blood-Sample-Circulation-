<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class DonorRequest extends Model
{

    protected $fillable = [

        'patient_id',
        'donor_id',
        'status',

    ];



    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }



    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

}