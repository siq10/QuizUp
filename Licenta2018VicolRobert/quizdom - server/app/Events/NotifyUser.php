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

class NotifyUser implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $sender;
    protected $receiver;
    protected $senderName;
    protected $time;
    protected $guid;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($guid, $sender,$receiver,$time,$senderName)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->time = $time;
        $this->senderName= $senderName;
        $this->guid= $guid;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notify.'.$this->receiver);
    }

    public function broadcastWith() {
        return [
            'sender' => $this->sender,
            'receiver' =>$this->receiver,
            'time' =>$this->time,
            'name' =>$this->senderName,
            'guid' => $this->guid
        ];
    }
}
