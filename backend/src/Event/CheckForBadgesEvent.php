<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class CheckForBadgesEvent extends Event
{
    public const NAME = 'check.badges';

    private int $userId;

    private int $campaignId;

    public function __construct(int $userId, int $campaignId)
    {
        $this->userId = $userId;
        $this->campaignId = $campaignId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }
}
