<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Campaign;
use App\Message\NotifyUsersAboutNewCampaign;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class CampaignNotificationSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleNewCampaignNotification', EventPriorities::POST_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function handleNewCampaignNotification(ViewEvent $event): void
    {
        $campaign = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$campaign instanceof Campaign || Request::METHOD_POST !== $method) {
            return;
        }

        $notification = new NotifyUsersAboutNewCampaign($campaign->getId());
        $this->bus->dispatch($notification);
    }
}