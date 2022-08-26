<?php

namespace App\Event;

use \Symfony\Contracts\EventDispatcher\Event;

final class NewUserRegisteredEvent extends Event
{
    /**
     * Event Identifier
     */
    public const NAME = 'user.registered';

    /**
     * @var int
     */
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}