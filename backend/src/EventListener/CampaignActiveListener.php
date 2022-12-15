<?php

namespace App\EventListener;

use App\Entity\Campaign;
use App\Message\NotifyUsersAboutNewCampaign;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class CampaignActiveListener
{
    public function __construct
    (
        private readonly MessageBusInterface $bus,
    ){}

    public function preUpdate(Campaign $campaign, LifecycleEventArgs $event)
    {
        if (!array_key_exists('active', $event->getEntityChangeSet()) || $event->getEntityChangeSet()['active'] == false) {
            return;
        }

        $notification = new NotifyUsersAboutNewCampaign($campaign->getId());
        $this->bus->dispatch($notification);
    }
}