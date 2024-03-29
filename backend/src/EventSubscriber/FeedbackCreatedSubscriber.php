<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PostFeedback;
use App\Enum\PointsReceivedType;
use App\Event\CampaignPointsReceivedEvent;
use App\Event\FeedbackCreatedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FeedbackCreatedSubscriber implements EventSubscriberInterface
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => 'array'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['handleFeedback', EventPriorities::POST_WRITE],
        ];
    }

    public function handleFeedback(ViewEvent $event): void
    {
        $feedback = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$feedback instanceof PostFeedback || Request::METHOD_POST !== $method) {
            return;
        }

        $createdEvent = new FeedbackCreatedEvent($feedback);
        $this->eventDispatcher->dispatch($createdEvent, FeedbackCreatedEvent::NAME);

        $feedbackPointsEvent = new CampaignPointsReceivedEvent(
            $feedback->getPost()->getCampaign()->getId(),
            $feedback->getUser()->getId(),
            PointsReceivedType::FEEDBACK->value
        );
        $this->eventDispatcher->dispatch($feedbackPointsEvent, CampaignPointsReceivedEvent::NAME);
    }
}
