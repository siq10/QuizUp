<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class StartGame implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $sender;
    protected $receiver;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($sender,$receiver)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('game.'.$this->sender);
    }

    public function broadcastWith() {
        return [
            'sender' => $this->sender,
            'receiver' =>$this->receiver
        ];
    }
}

