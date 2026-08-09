<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // These are the exact fields the university requirements asked for
    protected $fillable = [
        'blood_sample_id',
        'refrigerator',
        'shelf',
        'rack',
        'storage_location',
    ];
}