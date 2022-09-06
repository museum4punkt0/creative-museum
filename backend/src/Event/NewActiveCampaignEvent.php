<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class NewActiveCampaignEvent extends Event
{
    /**
     * Event Identifier.
     */
    public const NAME = 'campaign.new.active';

    protected int $campaignId;

    public function __construct(int $campaignId)
    {
        $this->campaignId = $campaignId;
    }

    public function getCampaignId(): int
    {
        return $this->campaignId;
    }
}
