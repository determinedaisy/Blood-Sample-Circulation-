<?php

namespace App\Notifications;

use App\Models\BloodSample;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BloodSampleRejectedNotification extends Notification
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
            'title' => 'Blood Sample Rejected',

            'message' =>
                'Your blood sample '
                . $this->bloodSample->sample_code
                . ' has been rejected.',

            'sample_code' =>
                $this->bloodSample->sample_code,

            'reason' =>
                $this->bloodSample->rejection_reason,

            'reviewed_at' =>
                $this->bloodSample->reviewed_at?->toDateTimeString(),
        ];
    }
}