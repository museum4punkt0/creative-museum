<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Entity\Campaign;
use App\Message\NotifyUsersAboutNewCampaign;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class CampaignActiveListener
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {
    }

    public function preUpdate(Campaign $campaign, LifecycleEventArgs $event)
    {
        if (!array_key_exists('active', $event->getEntityChangeSet()) || false == $event->getEntityChangeSet()['active']) {
            return;
        }

        $notification = new NotifyUsersAboutNewCampaign($campaign->getId());
        $this->bus->dispatch($notification);
    }
}
