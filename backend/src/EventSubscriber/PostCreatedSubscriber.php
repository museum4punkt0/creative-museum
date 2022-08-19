<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use App\Enum\PointsReceivedType;
use App\Event\CampaignPointsReceivedEvent;
use JetBrains\PhpStorm\ArrayShape;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Request;


class PostCreatedSubscriber implements EventSubscriberInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
    )
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['postCreated', EventPriorities::POST_WRITE],
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function postCreated(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method) {
            return;
        }

        if ($post->getParent()){
            $postPointsEvent = new CampaignPointsReceivedEvent(
                $post->getCampaign()->getId(),
                $post->getAuthor()->getId(),
                PointsReceivedType::COMMENT_CREATED->value
            );
        }else{
            $postPointsEvent = new CampaignPointsReceivedEvent(
                $post->getCampaign()->getId(),
                $post->getAuthor()->getId(),
                PointsReceivedType::POST_CREATED->value
            );
        }

        $this->eventDispatcher->dispatch($postPointsEvent, CampaignPointsReceivedEvent::NAME);
    }
}