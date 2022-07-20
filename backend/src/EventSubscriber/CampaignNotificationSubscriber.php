<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Message\NotifyUsersAboutNewCampaign;
use App\Repository\CampaignRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class CampaignNotificationSubscriber implements EventSubscriberInterface
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    /**
     * @var CampaignRepository
     */
    private CampaignRepository $campaignRepository;

    public function __construct(MessageBusInterface $bus, CampaignRepository $campaignRepository)
    {
        $this->bus = $bus;
        $this->campaignRepository = $campaignRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['handleNewCampaignNotification', EventPriorities::PRE_RESPOND],
        ];
    }

    /**
     * @param RequestEvent $event
     * @return void
     */
    public function handleNewCampaignNotification(ViewEvent $event): void
    {
        $campaigns = $this->campaignRepository->getUnnotifiedActive();

        if (empty($campaigns)){
            return;
        }

        foreach ($campaigns as $campaign) {
            $notification = new NotifyUsersAboutNewCampaign($campaign->getId());
            $this->bus->dispatch($notification);
        }
    }
}