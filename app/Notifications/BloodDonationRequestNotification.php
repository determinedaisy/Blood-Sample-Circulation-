<?php

namespace App\Notifications;

use App\Models\DonorRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BloodDonationRequestNotification extends Notification
{
    use Queueable;

    public DonorRequest $donorRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(DonorRequest $donorRequest)
    {
        $this->donorRequest = $donorRequest;
    }

    /**
     * Get the notification delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $emergencyRequest = $this->donorRequest
            ->loadMissing([
                'emergencyRequest',
                'patient.user',
            ])
            ->emergencyRequest;

        $patient = $this->donorRequest->patient;

        return [
            'type' => 'blood_donation_request',

            'title' => 'Emergency Blood Request',

            'message' => 'A patient has requested your blood donation for an emergency.',

            'donor_request_id' =>
                $this->donorRequest->id,

            'emergency_request_id' =>
                $this->donorRequest->emergency_request_id,

            'patient_id' =>
                $this->donorRequest->patient_id,

            'patient_name' =>
                $patient && $patient->user
                    ? $patient->user->name
                    : 'Emergency Patient',

            'donor_id' =>
                $this->donorRequest->donor_id,

            'status' =>
                $this->donorRequest->status,

            'blood_group' =>
                $emergencyRequest
                    ? $emergencyRequest->blood_group
                    : null,

            'latitude' =>
                $emergencyRequest
                    ? $emergencyRequest->latitude
                    : null,

            'longitude' =>
                $emergencyRequest
                    ? $emergencyRequest->longitude
                    : null,

            'action_url' =>
                route('donor.requests'),
        ];
    }
}