<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PinnacleSmsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $username;
    public $password;
    public $apikey;
    public $senderid;
    public $message;
    public $phone;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        $username,
        $password,
        $apikey,
        $senderid,
        $message,
        $phone
    )
    {
        $this->username = $username;
        $this->password = $password;
        $this->apikey = $apikey;
        $this->senderid = $senderid;
        $this->message = $message;
        $this->phone = $phone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
