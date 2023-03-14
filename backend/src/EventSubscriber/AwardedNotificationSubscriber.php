<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Awarded;
use App\Enum\MailType;
use App\Message\NotifyAboutNewAwarded;
use App\Service\MailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class AwardedNotificationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly MailService $mailService
    ) {
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleAwardNotification', EventPriorities::POST_WRITE],
        ];
    }

    public function handleAwardNotification(ViewEvent $event): void
    {
        $awarded = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$awarded instanceof Awarded || Request::METHOD_POST !== $method) {
            return;
        }

        $notification = new NotifyAboutNewAwarded($awarded->getId());
        $this->bus->dispatch($notification);

        $this->mailService->sendMail(MailType::AWARD_RECEIVED->value, $awarded->getWinner(), ['awarded' => $awarded]);
    }
}
