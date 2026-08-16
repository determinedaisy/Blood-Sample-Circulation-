<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'license_number',
        'specialization',
        'hospital',
        'phone',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}