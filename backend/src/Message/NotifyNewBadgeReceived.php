<?php

namespace App\Message;

class NotifyNewBadgeReceived
{
    /**
     * @var int
     */
    private int $userId;

    /**
     * @var int
     */
    private int $badgeId;

    public function __construct(int $userId, int $badgeId)
    {
        $this->userId = $userId;
        $this->badgeId = $badgeId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getBadgeId(): int
    {
        return $this->badgeId;
    }
}