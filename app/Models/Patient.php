<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'date_of_birth',
        'gender',
        'blood_group',
        'zone',
        'address_line',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function emergencyRequests()
{
    return $this->hasMany(EmergencyRequest::class);
}
}