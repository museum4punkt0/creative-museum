<?php

namespace App\EventSubscriber;

use App\Event\NewUserRegisteredEvent;
use App\Message\NotifyUserAboutRegister;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NewUserRegisteredEvent::NAME => 'onNewUserRegistered',
        ];
    }

    public function onNewUserRegistered(NewUserRegisteredEvent $event)
    {
        $registeredNotification = new NotifyUserAboutRegister($event->getUserId());

        $this->bus->dispatch($registeredNotification);
    }
}