<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pharmacy_order_id',
        'medicine_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(
            PharmacyOrder::class,
            'pharmacy_order_id'
        );
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}