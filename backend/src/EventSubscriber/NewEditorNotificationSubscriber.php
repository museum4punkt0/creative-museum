<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Notification;
use App\Enum\MailType;
use App\Service\MailService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewEditorNotificationSubscriber implements EventSubscriberInterface
{
    public function __construct
    (
        private readonly MailService $mailService
    )
    {
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => 'array'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['handleNewEditorNotification', EventPriorities::POST_WRITE],
        ];
    }

    public function handleNewEditorNotification(ViewEvent $event)
    {
        $notification = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$notification instanceof Notification || Request::METHOD_POST !== $method) {
            return;
        }

        if ($notification->isEditorNotification()){
            $this->mailService->sendMail(MailType::POST_COMMENTED->value,null,['notification' => $notification]);
        }
    }

}