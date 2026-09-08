<?php

namespace App\Notifications;

use App\Models\BloodSample;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LabStaffSampleAssignedNotification extends Notification
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
            'title' => 'Lab Examination Assigned',
            'message' => 'You have been assigned a blood sample for examination.',
            'sample_code' => $this->bloodSample->sample_code,
            'blood_sample_id' => $this->bloodSample->id,
            'url' => route('blood-samples.index'),
        ];
    }
}