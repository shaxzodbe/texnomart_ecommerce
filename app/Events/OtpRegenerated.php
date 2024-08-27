<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpRegenerated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $phoneNumber;

    public int $otp;

    /**
     * Create a new event instance.
     */
    public function __construct(string $phoneNumber, int $otp)
    {
        $this->phoneNumber = $phoneNumber;
        $this->otp = $otp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('otp-regenerated-channel'),
        ];
    }
}
