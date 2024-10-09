<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class OrderReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order, $url, $companyId;

    /**
     * Create a new event instance.
     */
    public function __construct($order, $url, $companyId)
    {
        $this->order = $order;
        $this->url = $url;
        $this->companyId = $companyId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return ['my-channel-' . $this->companyId];
    }

    public function broadcastAs()
    {
        return 'order-received';
    }

    public function broadcastWith()
    {
        return [
            'order' => $this->order,
            'url' => $this->url,
        ];
    }
}
