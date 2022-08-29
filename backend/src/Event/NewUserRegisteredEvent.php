<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class NewUserRegisteredEvent extends Event
{
    /**
     * Event Identifier.
     */
    public const NAME = 'user.registered';

    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
