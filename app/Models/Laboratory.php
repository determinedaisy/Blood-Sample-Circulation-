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

    protected function casts(): array
    {
        return [
            'daily_capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function transportations()
    {
        return $this->hasMany(SampleTransportation::class);
    }
}