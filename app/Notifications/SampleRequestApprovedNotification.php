<?php

namespace App\Notifications;

use App\Models\SampleRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SampleRequestApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public SampleRequest $sampleRequest
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'kind' => 'sample_request_approved',
            'title' => 'Sample request approved',
            'message' => 'Your '.$this->sampleRequest->sample_type.' request has been approved and is ready for collection scheduling.',
            'sample_request_id' => $this->sampleRequest->id,
            'sample_code' => $this->sampleRequest->bloodSample?->sample_code,
            'status' => 'approved',
        ];
    }
}
