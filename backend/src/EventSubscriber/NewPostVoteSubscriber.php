<?php

namespace App\EventSubscriber;

use App\Enum\VoteDirection;
use App\Event\NewPostVoteEvent;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewPostVoteSubscriber implements EventSubscriberInterface
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NewPostVoteEvent::NAME => 'onPostVoteReceived',
        ];
    }

    /**
     * @param NewPostVoteEvent $event
     * @return void
     */
    public function onPostVoteReceived(NewPostVoteEvent $event)
    {
        $post = $this->postRepository->find($event->getPostId());

        if (!$post) {
            return;
        }

        if ($event->getDirection() == VoteDirection::UP->value) {
            $post->setUpvotes($post->getUpvotes() + $event->getVoteDifference());

            if ($event->getSwitched()) {
                $post->setDownvotes($post->getDownvotes() - 1);
                $post->setVotestotal($post->getVotestotal() + 2);
            } else {
                $post->setVotestotal($post->getVotestotal() + $event->getVoteDifference());
            }
        } elseif ($event->getDirection() == VoteDirection::DOWN->value) {
            $post->setDownvotes($post->getDownvotes() + $event->getVoteDifference());

            if ($event->getSwitched()) {
                $post->setUpvotes($post->getUpvotes() - 1);
                $post->setVotestotal($post->getVotestotal() - 2);
            } else {
                $post->setVotestotal($post->getVotestotal() - $event->getVoteDifference());
            }
        }

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}