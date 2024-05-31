<?php

use Symfony\Contracts\EventDispatcher\Event;

class NewUserEvent extends Event
{
    private ?string $email = null;

    public function __construct(string $email)
    {
        $this->email = $email;
    }


    /**
     * Get the value of email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
