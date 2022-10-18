<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Message\NotifyUsersAboutNewCampaign;
use App\Repository\CampaignRepository;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class CampaignNotificationSubscriber implements EventSubscriberInterface
{

    public function __construct
    (
        private readonly MessageBusInterface $bus,
        private readonly CampaignRepository $campaignRepository,
    ){}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['handleNewCampaignNotification', EventPriorities::PRE_RESPOND],
        ];
    }

    /**
     * @param RequestEvent $event
     */
    public function handleNewCampaignNotification(ViewEvent $event): void
    {
        $campaigns = $this->campaignRepository->getUnnotifiedActive();

        if (empty($campaigns)) {
            return;
        }

        foreach ($campaigns as $campaign) {
            $notification = new NotifyUsersAboutNewCampaign($campaign->getId());
            $this->bus->dispatch($notification);
        }
    }
}
