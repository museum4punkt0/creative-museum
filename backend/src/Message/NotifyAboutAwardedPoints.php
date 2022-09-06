<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Message;

class NotifyAboutAwardedPoints
{
    private int $userId;

    private int $points;

    private int $campaignId;

    public function __construct(int $userId, int $points, int $campaignId)
    {
        $this->userId = $userId;
        $this->points = $points;
        $this->campaignId = $campaignId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPoints(): int
    {
        return $this->points;
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }
}
