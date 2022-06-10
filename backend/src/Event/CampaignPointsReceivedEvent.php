<?php

namespace App\Event;

use \Symfony\Contracts\EventDispatcher\Event;

class CampaignPointsReceivedEvent extends Event
{
    /**
     * Event Identifier
     */
    public const NAME = 'campaign.points.received';

    /**
     * @var int
     */
    protected int $campaignId;

    /**
     * @var int
     */
    protected int $receiverId;

    public function __construct(int $campaignId, int $receiverId)
    {
        $this->campaignId = $campaignId;
        $this->receiverId = $receiverId;
    }

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    /**
     * @return int
     */
    public function getReceiverId(): int
    {
        return $this->receiverId;
    }
}