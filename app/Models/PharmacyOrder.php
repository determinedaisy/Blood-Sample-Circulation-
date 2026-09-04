<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'delivery_address',
        'status',
        'total_amount',
        'accepted_by',
        'accepted_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'accepted_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'accepted_by');
    }

    public function items()
    {
        return $this->hasMany(PharmacyOrderItem::class);
    }
}