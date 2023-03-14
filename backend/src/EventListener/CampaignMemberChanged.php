<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Entity\CampaignMember;
use App\Event\CheckForBadgesEvent;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Psr\EventDispatcher\EventDispatcherInterface;

class CampaignMemberChanged
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function preUpdate(CampaignMember $campaignMember, PreUpdateEventArgs $event): void
    {
        if (!array_key_exists('score', $event->getEntityChangeSet()) && !array_key_exists('rewardPoints', $event->getEntityChangeSet())) {
            return;
        }

        $checkForBadgesEvent = new CheckForBadgesEvent($campaignMember->getUser()->getId(), $campaignMember->getCampaign()->getId());
        $this->eventDispatcher->dispatch($checkForBadgesEvent, CheckForBadgesEvent::NAME);
    }
}
