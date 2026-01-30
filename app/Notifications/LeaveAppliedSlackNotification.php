<?php

namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;

class LeaveAppliedSlackNotification extends Notification
{
    use Queueable;

    public function __construct(public LeaveRequest $leave)
    {
    }

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $employee   = $this->leave->employee;
        $department = $employee->department?->name ?? 'N/A';

        return (new SlackMessage)
            ->headerBlock('📌 New Leave Request Submitted')

            ->sectionBlock(function (SectionBlock $section) use ($employee, $department) {
                $section->text(
                    "*Employee:* {$employee->full_name}\n".
                    "*Department:* {$department}\n".
                    "*Leave Type:* {$this->leave->leaveType->name}\n".
                    "*Dates:* {$this->leave->start_date} → {$this->leave->end_date}\n".
                    "*Status:* ".strtoupper($this->leave->status)
                );
            });
    }
}
