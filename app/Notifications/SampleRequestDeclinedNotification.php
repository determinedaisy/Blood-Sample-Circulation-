<?php

namespace App\Notifications;

use App\Models\SampleRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SampleRequestDeclinedNotification extends Notification
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
        'kind' => 'sample_request_declined',
        'title' => 'Sample Request Declined',
        'message' => 'Your '.$this->sampleRequest->sample_type.' request has been declined by the administrator.',
        'reason' => $this->sampleRequest->decline_reason,
        'sample_request_id' => $this->sampleRequest->id,
        'sample_code' => $this->sampleRequest->bloodSample?->sample_code,
        'status' => 'declined',
    ];
}
}
