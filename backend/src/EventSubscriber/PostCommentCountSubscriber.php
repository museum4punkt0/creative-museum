<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use App\Repository\PostRepository;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostCommentCountSubscriber implements EventSubscriberInterface
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
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

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function increaseCommentCount(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method || !$this->isComment($post)) {
            return;
        }

        $this->postRepository->increaseCommentCount($post);
    }

    /**
     * @param Post $post
     * @return bool
     */
    private function isComment(Post $post): bool
    {
        if (is_null($post->getParent())){
            return false;
        }

        return true;
    }
}