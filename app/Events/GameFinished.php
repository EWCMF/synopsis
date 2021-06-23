<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameFinished implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $state;
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
        return new PresenceChannel('game.'.$this->id);
    }

    public function broadcastWith()
    {
        return [
            'log' => $this->state->getCurrentMessageToLog(),
            'currentTurn' => $this->state->getCurrentTurn(),
            'turnSequence' => $this->state->getTurnSequence(),
        ];
    }
}
