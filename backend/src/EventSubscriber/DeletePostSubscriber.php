<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\VotesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DeletePostSubscriber implements EventSubscriberInterface
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var VotesRepository
     */
    private VotesRepository $votesRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, VotesRepository $votesRepository ,EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->votesRepository = $votesRepository;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onDeletePostAction', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onDeletePostAction(ViewEvent $event)
    {
        $post = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (!$post instanceof Post || Request::METHOD_DELETE !== $method) {
            return;
        }

        if ($post->getParent()) {
            $parent = $this->postRepository->find($post->getParent()->getId());
            $parent->setCommentCount($parent->getCommentCount() - 1);
            $this->entityManager->persist($parent);
            $this->entityManager->flush();
        } else {
            $votes = $this->votesRepository->findBy([
                'post' => $post->getId()
            ]);
            foreach ($votes as $vote){
                $this->votesRepository->remove($vote);
            }

            $comments = $this->postRepository->findBy([
                'parent' => $post->getId()
            ]);
            foreach ($comments as $comment) {
                $this->postRepository->remove($comment);
            }
        }
    }
}