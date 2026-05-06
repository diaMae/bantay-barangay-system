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
            'report_id'   => $this->report->id,
            'title'       => $this->report->title,
            'status'      => $this->report->status,
            'admin_notes' => $this->report->admin_notes,
            'message'     => "Your report \"{$this->report->title}\" has been updated to: {$status}.",
            'url'         => route('reports.show', $this->report->id),
        ];
    }
}
