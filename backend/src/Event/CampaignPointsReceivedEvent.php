<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class CampaignPointsReceivedEvent extends Event
{
    /**
     * Event Identifier.
     */
    public const NAME = 'campaign.points.received';

    protected int $campaignId;

    protected int $receiverId;

    protected string $pointsType;

    public function __construct(int $campaignId, int $receiverId, string $pointsType)
    {
        $this->campaignId = $campaignId;
        $this->receiverId = $receiverId;
        $this->pointsType = $pointsType;
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function getPointsType(): string
    {
        return $this->pointsType;
    }
}
