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

    /**
     * Donor belongs to a user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All blood samples donated by this donor.
     */
    public function bloodSamples(): HasMany
    {
        return $this->hasMany(BloodSample::class, 'donor_id');
    }

    /**
     * Count only successfully accepted donations.
     */
    public function successfulDonationCount(): int
    {
        return $this->bloodSamples()
            ->where('status', 'accepted')
            ->count();
    }

    /**
     * Recalculate the donor's badge information.
     *
     * Only accepted blood donations count.
     */
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

    /**
     * Get a human-readable donor badge name.
     */
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