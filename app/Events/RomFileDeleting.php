<?php

namespace App\Events;

use App\Models\RomFile;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
#use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
#use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RomFileDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public RomFile $romFile;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(RomFile $romFile)
    {
        $this->romFile = $romFile;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        return new PrivateChannel('channel-name');
    }
}
