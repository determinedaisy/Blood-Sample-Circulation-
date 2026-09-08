<?php

namespace App\Notifications;

use App\Models\DonorRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BloodDonationRequestNotification extends Notification
{
    use Queueable;

    public function __construct(
        public DonorRequest $donorRequest
    ) {
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $patientName =
            $this->donorRequest
                ->patient
                ->user
                ->name
                ?? 'A patient';

        return [
            'type' => 'emergency_blood_request',

            'title' =>
                'Emergency Blood Request',

            'message' =>
                $patientName .
                ' has requested your blood donation.',

            'donor_request_id' =>
                $this->donorRequest->id,

            'emergency_request_id' =>
                $this->donorRequest
                    ->emergency_request_id,

            'patient_id' =>
                $this->donorRequest->patient_id,
        ];
    }
}