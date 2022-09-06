<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Message;

class NotifyUserAboutReportingSuccess
{
    private int $userId;

    private int $postId;

    public function __construct(int $userId, int $postId)
    {
        $this->userId = $userId;
        $this->postId = $postId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }
}
