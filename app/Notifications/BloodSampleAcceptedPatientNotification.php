<?php

namespace App\Notifications;

use App\Models\BloodSample;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BloodSampleAcceptedPatientNotification extends Notification
{
    use Queueable;

    public function __construct(
        public BloodSample $bloodSample
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'blood_sample_accepted',
            'title' => 'Blood Sample Accepted',
            'message' => 'Your blood sample has been examined and accepted successfully.',
            'sample_code' => $this->bloodSample->sample_code,
            'blood_sample_id' => $this->bloodSample->id,
            'status' => 'accepted',
            'reviewed_at' => $this->bloodSample->reviewed_at?->toDateTimeString(),
            'url' => route('blood-samples.index'),
        ];
    }
}
