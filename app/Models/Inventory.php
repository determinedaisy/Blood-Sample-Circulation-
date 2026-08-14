<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

   
    protected $fillable = [
        'blood_sample_id',
        'refrigerator',
        'shelf',
        'rack',
        'storage_location',
    ];
}