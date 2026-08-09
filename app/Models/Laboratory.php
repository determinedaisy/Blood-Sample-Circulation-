<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'daily_capacity',
        'is_active',
    ];

    public function transportations()
    {
        return $this->hasMany(SampleTransportation::class);
    }
}