<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use App\Entity\User;
use App\Message\NotifyUserAboutReportingSuccess;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

class PostReportedSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $bus;

    private Security $security;

    public function __construct(MessageBusInterface $bus, Security $security)
    {
        $this->bus = $bus;
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['handlePostReportedNotification', EventPriorities::POST_WRITE],
        ];
    }

    public function handlePostReportedNotification(ViewEvent $event)
    {
        $post = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (!$post instanceof Post || Request::METHOD_PATCH !== $method || !$post->getReported()) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        $notification = new NotifyUserAboutReportingSuccess($user->getId(), $post->getId());
        $this->bus->dispatch($notification);
    }
}
