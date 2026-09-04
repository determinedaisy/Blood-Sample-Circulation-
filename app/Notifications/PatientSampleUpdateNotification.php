<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PatientSampleUpdateNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $kind,
        public string $title,
        public string $message,
        public ?int $sampleRequestId = null,
        public ?int $sampleReportId = null,
        public ?string $sampleCode = null,
        public ?string $actionLabel = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'kind' => $this->kind,
            'title' => $this->title,
            'message' => $this->message,
            'sample_request_id' => $this->sampleRequestId,
            'sample_report_id' => $this->sampleReportId,
            'sample_code' => $this->sampleCode,
            'action_label' => $this->actionLabel,
        ];
    }
}
