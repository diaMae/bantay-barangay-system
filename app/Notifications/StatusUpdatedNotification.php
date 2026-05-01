<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Notifications\Notification;

class StatusUpdatedNotification extends Notification
{
    public function __construct(public Report $report) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = ucfirst(str_replace('_', ' ', $this->report->status));

        return [
            'report_id' => $this->report->id,
            'message'   => "Your report \"{$this->report->title}\" status has been updated to: {$status}.",
        ];
    }
}
