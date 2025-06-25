<?php

namespace App\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewUserRegistered
{
    use Dispatchable, SerializesModels;

    public $userData;
    public $typeMail;

    public function __construct(array $userData, string $typeMail)
    {
        $this->userData = $userData;
        $this->typeMail = $typeMail;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
   
}
