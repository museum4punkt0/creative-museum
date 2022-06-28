<?php

namespace App\Message;

class NotifyUsersAboutNewCampaign
{
    /**
     * @var int
     */
    private int $campaignId;

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