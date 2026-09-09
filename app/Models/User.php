<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'donation_count',
    'donor_badge',
    'shop_discount',
    'donor_priority',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }

    public function donorProfile()
    {
        return $this->hasOne(Donor::class, 'user_id');
    }

    public function donorReviews()
    {
        return $this->hasMany(DonorReview::class, 'donor_id');
    }

    public function doctorReviews()
    {
        return $this->hasMany(DonorReview::class, 'doctor_id');
    }

    public function donorApplications()
    {
        return $this->hasMany(DonorApplication::class, 'patient_id');
    }

    public function reviewedDonorApplications()
    {
        return $this->hasMany(DonorApplication::class, 'doctor_id');
    }

    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class, 'patient_id');
    }

    public function createdSampleRequests()
    {
        return $this->hasMany(SampleRequest::class, 'requested_by');
    }

    public function bloodDonations()
    {
        return $this->hasMany(BloodSample::class, 'patient_id');
    }

    public function successfulDonationCount(): int
    {
        return $this->bloodDonations()
            ->where('status', 'accepted')
            ->count();
    }

    public function updateDonorBadge(): void
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

    public function getDonorBadgeNameAttribute(): string
    {
        return match ($this->donor_badge) {
            'bronze' => 'Bronze Donor',
            'silver' => 'Silver Donor',
            'gold' => 'Gold Donor',
            'platinum' => 'Platinum Donor',
            default => 'No Badge',
        };
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }

    public function forumComments()
    {
        return $this->hasMany(ForumComment::class);
    }

    public function forumReactions()
    {
        return $this->hasMany(ForumReaction::class);
    }
}

