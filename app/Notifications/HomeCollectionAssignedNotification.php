<?php

namespace App\Notifications;

use App\Models\HomeCollectionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class HomeCollectionAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public HomeCollectionRequest $homeCollection
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Home Collection Assigned',
            'message' => 'You have been assigned to collect a blood sample from '
                . $this->homeCollection->address
                . ' during '
                . $this->homeCollection->preferred_time
                . '.',
            'home_collection_id' => $this->homeCollection->id,
            'address' => $this->homeCollection->address,
            'preferred_date' => $this->homeCollection->preferred_date,
            'preferred_time' => $this->homeCollection->preferred_time,
            'url' => route('home-collections.collector.index'),
        ];
    }
}