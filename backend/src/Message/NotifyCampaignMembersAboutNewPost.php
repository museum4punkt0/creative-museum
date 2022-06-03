<?php

namespace App\Message;

class NotifyCampaignMembersAboutNewPost
{
    private int $postId;

    public function __construct(int $postId)
    {
        $this->postId = $postId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }
}