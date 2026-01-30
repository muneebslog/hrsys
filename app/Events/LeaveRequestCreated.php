<?php

namespace App\Events;

use App\Models\LeaveRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaveRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $leaveRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
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
        return 'leave-request.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->leaveRequest->id,
            'start_date' => $this->leaveRequest->start_date,
            'end_date' => $this->leaveRequest->end_date,
            'status' => $this->leaveRequest->status,
            'created_at' => $this->leaveRequest->created_at->toISOString(),
            'employee' => [
                'id' => $this->leaveRequest->employee->id,
                'first_name' => $this->leaveRequest->employee->first_name,
                'last_name' => $this->leaveRequest->employee->last_name,
            ],
            'leave_type' => [
                'id' => $this->leaveRequest->leaveType->id,
                'name' => $this->leaveRequest->leaveType->name,
            ],
        ];
    }
}
