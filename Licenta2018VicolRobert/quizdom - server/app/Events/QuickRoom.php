<?php
/**
 * Created by PhpStorm.
 * User: siq13
 * Date: 6/1/18
 * Time: 8:25 PM
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class QuickRoom implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    protected $user;
    protected $status;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$status)
    {
        $this->user = $user;
        $this->status = $status;
//        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('quickroom');
    }


    /**
     * @return array
     * status = 0 for leaving
     * status = 1 for entering
     *     (the game room)
     */
    public function broadcastWith() {
        return [
            'user' => $this->user,
            'status' => $this->status
        ];
    }
}
