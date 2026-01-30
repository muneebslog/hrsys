<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Slack\SlackMessage;
use Illuminate\Notifications\Slack\BlockKit\Blocks\SectionBlock;

class ComplaintSubmittedSlackNotification extends Notification
{
    use Queueable;

    public function __construct(public Complaint $complaint)
    {
    }

    public function via(object $notifiable): array
    {
        return ['slack'];
    }

    public function toSlack(object $notifiable): SlackMessage
    {
        $employeeName = $this->complaint->is_anonymous
            ? 'Anonymous'
            : ($this->complaint->employee?->full_name ?? 'Unknown');

        return (new SlackMessage)
            ->headerBlock('📢 New Staff Submission')

            ->sectionBlock(function (SectionBlock $section) use ($employeeName) {
                $section->text(
                    "*Type:* " . ucfirst($this->complaint->category) . "\n" .
                    "*Title:* {$this->complaint->title}\n" .
                    "*Submitted By:* {$employeeName}\n" .
                    "*Status:* " . strtoupper($this->complaint->status)
                );
            })

            ->sectionBlock(function (SectionBlock $section) {
                $section->text(
                    "*Message:*\n" .
                    str($this->complaint->description)->limit(800)
                );
            });
    }
}
