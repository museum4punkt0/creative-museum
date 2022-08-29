<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Message;

class NotifyNewBadgeReceived
{
    private int $userId;

    private int $badgeId;

    public function __construct(int $userId, int $badgeId)
    {
        $this->userId = $userId;
        $this->badgeId = $badgeId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getBadgeId(): int
    {
        return $this->badgeId;
    }
}
