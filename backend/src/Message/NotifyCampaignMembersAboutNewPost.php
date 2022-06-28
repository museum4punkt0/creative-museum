<?php

namespace App\Message;

class NotifyCampaignMembersAboutNewPost
{
    /**
     * @var int
     */
    private int $postId;

    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }
}