<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SampleTransportation;
class BloodSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_code',
        'patient_id',
        'collected_by',
        'sample_type',
        'status',
        'collected_at',
        'quality_checks',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'blood_type',
        'donor_id',
    ];

    protected function casts(): array
    {
        return [
            'quality_checks' => 'array',
            'collected_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function transportations()
{
    return $this->hasMany(SampleTransportation::class);
}
public function overallStatus(): string
{
    // Final lab result always wins.
    if ($this->status === 'accepted') {
        return 'accepted';
    }

    if ($this->status === 'rejected') {
        return 'rejected';
    }

    // Look at connected sample request.
    $request = $this->sampleRequest;

    if ($request && $request->status === 'declined') {
        return 'declined';
    }

    // Look at connected transportation.
    $transportation = $this->transportations()
        ->latest()
        ->first();

    if ($transportation) {
        if ($transportation->status === 'delivered') {
            return 'delivered';
        }

        if ($transportation->status === 'in_transit') {
            return 'in_transit';
        }

        if ($transportation->status === 'pending') {
            return 'collector_assigned';
        }
    }

    if ($request && $request->status === 'approved') {
        return 'approved';
    }

    return 'pending';
}
    public function collector()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }
   public function sampleRequest()
{
    return $this->hasOne(SampleRequest::class);
}
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
    
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function sampleReport()
    {
        return $this->hasOne(SampleReport::class);
    }
    public function donor()
{
    return $this->belongsTo(Donor::class, 'donor_id');
}
}
