<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donor extends Model
{

    protected $fillable = [
        'user_id',
        'blood_group',
        'phone',
        'latitude',
        'longitude',
        'is_willing',
        'is_available',
        'is_verified',
    ];


    protected $casts = [
        'is_willing' => 'boolean',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
    ];



    /**
     * Donor belongs to a User account
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}