<?php

namespace App\Message;

class NotifyUserAboutReportingSuccess
{
    /**
     * @var int
     */
    private int $userId;

    /**
     * @var int
     */
    private int $postId;

    public function __construct(int $userId, int $postId)
    {
        $this->userId = $userId;
        $this->postId = $postId;
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
    public function getPostId(): int
    {
        return $this->postId;
    }
}