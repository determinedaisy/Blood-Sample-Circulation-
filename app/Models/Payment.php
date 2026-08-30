<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_sample_id',
        'patient_id',
        'amount',
        'invoice_number',
        'bkash_payment_id',
        'transaction_id',
        'status',
    ];

    public function bloodSample()
    {
        return $this->belongsTo(BloodSample::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}