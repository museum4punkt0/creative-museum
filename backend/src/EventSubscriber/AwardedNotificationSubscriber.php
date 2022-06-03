<?php

namespace App\EventSubscriber;

use App\Entity\Awarded;
use App\Message\NotifyUserAboutNewAwarded;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;


class AwardedNotificationSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['handleAwardNotification', EventPriorities::POST_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function handleAwardNotification(ViewEvent $event): void
    {
        $awarded = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$awarded instanceof Awarded || Request::METHOD_POST !== $method) {
            return;
        }

        $notification = new NotifyUserAboutNewAwarded($awarded->getId());
        $this->bus->dispatch($notification);
    }
}