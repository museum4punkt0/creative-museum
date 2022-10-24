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
use App\Enum\MailType;
use App\Enum\PointsReceivedType;
use App\Event\CampaignPointsReceivedEvent;
use App\Service\MailService;
use JetBrains\PhpStorm\ArrayShape;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly MailService $mailService
    ) {}

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => 'array'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['postCreated', EventPriorities::POST_WRITE],
        ];
    }

    public function postCreated(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method) {
            return;
        }

        if ($post->getParent()) {
            $postPointsEvent = new CampaignPointsReceivedEvent(
                $post->getCampaign()->getId(),
                $post->getAuthor()->getId(),
                PointsReceivedType::COMMENT_CREATED->value
            );
            if ($post->getAuthor() !== $post->getParent()->getAuthor()){
                $this->mailService->sendMail(MailType::POST_COMMENTED->value,$post->getParent()->getAuthor(),['comment' => $post]);
            }
        } else {
            $postPointsEvent = new CampaignPointsReceivedEvent(
                $post->getCampaign()->getId(),
                $post->getAuthor()->getId(),
                PointsReceivedType::POST_CREATED->value
            );
        }

        $this->eventDispatcher->dispatch($postPointsEvent, CampaignPointsReceivedEvent::NAME);
    }
}
