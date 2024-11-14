<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SGNotificationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $notificationdate;
    public $notificationId;
    public $notificationmessage;

    public function __construct($notificationdate, $notificationId, $notificationmessage)
    {
        $this->notificationdate = $notificationdate;
        $this->notificationId = $notificationId;
        $this->notificationmessage = $notificationmessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
