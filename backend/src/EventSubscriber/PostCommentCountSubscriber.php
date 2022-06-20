<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostCommentCountSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['increaseCommentCount', EventPriorities::POST_WRITE],
        ];
    }

    public function increaseCommentCount(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method || !$this->isComment($post)) {
            return;
        }

        $this->entityManager->persist($post->getParent()->increaseCommentCount());
        $this->entityManager->flush();
    }

    private function isComment(Post $post): bool
    {
        if (is_null($post->getParent())){
            return false;
        }

        return true;
    }
}