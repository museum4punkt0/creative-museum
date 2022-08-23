<?php

namespace App\Message;

class NotifyAboutPostCreatedPoints
{
    /**
     * @var int
     */
    private int $userId;

    /**
     * @var int
     */
    private int $points;

    /**
     * @var int
     */
    private int $campaignId;

    public function __construct(int $userId, int $points,int $campaignId)
    {
        $this->userId = $userId;
        $this->points = $points;
        $this->campaignId = $campaignId;
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
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }
}