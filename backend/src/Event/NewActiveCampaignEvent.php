<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class NewActiveCampaignEvent extends Event
{
    /**
     * Event Identifier
     */
    public const NAME = 'campaign.new.active';

    /**
     * @var int
     */
    protected int $campaignId;

    public function __construct(int $campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * @return int
     */
    public function getCampaignId(): int
    {
        return $this->campaignId;
    }
}