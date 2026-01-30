<?php

namespace App\Events;

use App\Models\Complaint;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ComplaintCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $complaint;

    /**
     * Create a new event instance.
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('admin-notifications');
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'complaint.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->complaint->id,
            'category' => $this->complaint->category,
            'title' => $this->complaint->title,
            'status' => $this->complaint->status,
            'created_at' => $this->complaint->created_at->toISOString(),
            'employee' => $this->complaint->employee ? [
                'id' => $this->complaint->employee->id,
                'first_name' => $this->complaint->employee->first_name,
                'last_name' => $this->complaint->employee->last_name,
            ] : null,
        ];
    }
}
