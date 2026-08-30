<?php

namespace App\Notifications;

use App\Models\BloodSample;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BloodSampleAcceptedNotification extends Notification
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
        $donor = $this->bloodSample->donor;

        return [
            'title' => 'Blood Donation Accepted',

            'message' =>
                'Your blood sample '
                . $this->bloodSample->sample_code
                . ' has been accepted.',

            'sample_code' =>
                $this->bloodSample->sample_code,

            'donation_count' =>
                $donor?->donation_count ?? 0,

            'donor_badge' =>
                $donor?->donor_badge ?? 'none',

            'badge_name' =>
                $donor?->badge_name ?? 'No Badge',

            'shop_discount' =>
                $donor?->shop_discount ?? 0,

            'reviewed_at' =>
                $this->bloodSample->reviewed_at?->toDateTimeString(),
        ];
    }
}