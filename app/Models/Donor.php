<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'donation_count',
        'donor_badge',
        'shop_discount',
        'donor_priority',
    ];

    protected $casts = [
        'is_willing' => 'boolean',
        'is_available' => 'boolean',
        'is_verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bloodSamples(): HasMany
    {
        return $this->hasMany(
            BloodSample::class,
            'donor_id'
        );
    }

    public function donorRequests(): HasMany
    {
        return $this->hasMany(
            DonorRequest::class,
            'donor_id'
        );
    }

    public function successfulDonationCount(): int
    {
        $acceptedBloodSamples = $this->bloodSamples()
            ->where('status', 'accepted')
            ->count();

        $completedEmergencyDonations = $this->donorRequests()
            ->where('status', 'completed')
            ->count();

        return $acceptedBloodSamples + $completedEmergencyDonations;
    }

    public function updateBadge(): void
    {
        $count = $this->successfulDonationCount();

        if ($count >= 20) {
            $badge = 'platinum';
            $discount = 20;
            $priority = 4;
        } elseif ($count >= 10) {
            $badge = 'gold';
            $discount = 15;
            $priority = 3;
        } elseif ($count >= 5) {
            $badge = 'silver';
            $discount = 10;
            $priority = 2;
        } elseif ($count >= 3) {
            $badge = 'bronze';
            $discount = 5;
            $priority = 1;
        } else {
            $badge = 'none';
            $discount = 0;
            $priority = 0;
        }

        $this->update([
            'donation_count' => $count,
            'donor_badge' => $badge,
            'shop_discount' => $discount,
            'donor_priority' => $priority,
        ]);
    }

    public function getBadgeNameAttribute(): string
    {
        return match ($this->donor_badge) {
            'bronze' => 'Bronze Donor',
            'silver' => 'Silver Donor',
            'gold' => 'Gold Donor',
            'platinum' => 'Platinum Donor',
            default => 'No Badge',
        };
    }
}
