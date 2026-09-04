<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_order_id',
        'equipment_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            EquipmentOrder::class,
            'equipment_order_id'
        );
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
