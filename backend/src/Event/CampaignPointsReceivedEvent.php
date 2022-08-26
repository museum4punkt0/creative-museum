<?php

namespace App\Event;

use \Symfony\Contracts\EventDispatcher\Event;

final class CampaignPointsReceivedEvent extends Event
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

    /**
     * @var string
     */
    protected string $pointsType;

    public function __construct(int $campaignId, int $receiverId, string $pointsType)
    {
        $this->campaignId = $campaignId;
        $this->receiverId = $receiverId;
        $this->pointsType = $pointsType;
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

    /**
     * @return string
     */
    public function getPointsType(): string
    {
        return $this->pointsType;
    }
}