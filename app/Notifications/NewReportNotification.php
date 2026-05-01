<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification
{
    public function __construct(public Report $report) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'report_id' => $this->report->id,
            'message'   => "New report submitted: \"{$this->report->title}\" by {$this->report->user->name}.",
        ];
    }
}
