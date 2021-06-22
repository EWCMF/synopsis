<?php

namespace App\Events;

use App\Classes\State;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerSpecificInfo implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public State $state;
    protected $id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $state)
    {
        $this->state = $state;
        $this->id = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user.'.$this->id);
    }

    public function broadcastWith()
    {
        return [
            'notes' => $this->state->getPlayerNotes()[$this->id],
            'ownHand' => $this->state->getCardsInHandForUser($this->id),
        ];
    }
}
