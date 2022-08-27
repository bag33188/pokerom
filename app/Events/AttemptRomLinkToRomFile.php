<?php

namespace App\Events;

use App\Models\Rom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttemptRomLinkToRomFile
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Rom $rom;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Rom $rom)
    {
        $this->rom = $rom;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel|array
    {
        return new PrivateChannel('channel-name');
    }
}
