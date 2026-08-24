<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ADDED 'role' TO THE FILLABLE ATTRIBUTE BELOW
#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    public function donorReviews()
    {
        return $this->hasMany(DonorReview::class, 'donor_id');
    }

    public function patientProfile()
    {
        return $this->hasOne(Patient::class);
    }

    public function doctorReviews()
    {
        return $this->hasMany(DonorReview::class, 'doctor_id');
    }

    public function sampleRequests()
    {
        return $this->hasMany(SampleRequest::class, 'patient_id');
    }

    public function createdSampleRequests()
    {
        return $this->hasMany(SampleRequest::class, 'requested_by');
    }
}