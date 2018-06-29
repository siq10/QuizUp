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

class RespondToGameInvitation implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $accepted;
    protected $data;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($accepted,$data)
    {
        $this->accepted = $accepted;
//        $this->opponentid = $opponentid;
//        $this->opponentname = $opponentname;
        $this->data = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('notify.'.$this->data["userid2"]);
    }

    public function broadcastWith() {
        return [
            'accepted' =>$this->accepted,
            'data' => $this->data
            ];
    }
}
