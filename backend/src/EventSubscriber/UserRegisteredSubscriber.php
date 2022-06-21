<?php

namespace App\EventSubscriber;

use App\Event\NewUserRegisteredEvent;
use App\Message\NotifyUserAboutRegister;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class UserRegisteredSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                ['onKernelResponsePre', 10],
                ['onKernelResponsePost', -10],
            ],
            NewUserRegisteredEvent::NAME => 'onNewUserRegistered',
        ];
    }

    public function onKernelResponsePre(ResponseEvent $event)
    {
    }

    public function onKernelResponsePost(ResponseEvent $event)
    {
    }

    public function onNewUserRegistered(NewUserRegisteredEvent $event)
    {
        $registeredNotification = new NotifyUserAboutRegister($event->getUserId());

        $this->bus->dispatch($registeredNotification);
    }
}