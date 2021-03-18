<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EntityRatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Model $rateable;
    private User $user;
    private float $score;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $rateable, User $user, float $score)
    {
        $this->rateable = $rateable;
        $this->user = $user;
        $this->score = $score;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getRateable(): Model
    {
        return $this->rateable;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    /*public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }*/
}
