<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Enum\VoteDirection;
use App\Event\NewPostVoteEvent;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewPostVoteSubscriber implements EventSubscriberInterface
{
    private PostRepository $postRepository;

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
     * @return void
     */
    public function onPostVoteReceived(NewPostVoteEvent $event)
    {
        $post = $this->postRepository->find($event->getPostId());

        if (!$post) {
            return;
        }

        if ($event->getDirection() === VoteDirection::UP->value) {
            $post->setUpvotes($post->getUpvotes() + 1);
            $post->setVotestotal($post->getVotestotal() + 1);

            if ($event->getOldDirection() === VoteDirection::DOWN->value) {
                $post->setDownvotes($post->getDownvotes() - 1);
                $post->setVotestotal($post->getVotestotal() + 1);
            }
        } elseif ($event->getDirection() === VoteDirection::DOWN->value) {
            $post->setDownvotes($post->getDownvotes() + 1);
            $post->setVotestotal($post->getVotestotal() - 1);

            if ($event->getOldDirection() === VoteDirection::UP->value) {
                $post->setUpvotes($post->getUpvotes() - 1);
                $post->setVotestotal($post->getVotestotal() - 1);
            }
        } elseif ($event->getDirection() === VoteDirection::NONE->value) {
            if ($event->getOldDirection() == VoteDirection::UP->value) {
                $post->setUpvotes($post->getUpvotes() - 1);
                $post->setVotestotal($post->getVotestotal() - 1);
            } elseif ($event->getOldDirection() == VoteDirection::DOWN->value) {
                $post->setDownvotes($post->getDownvotes() - 1);
                $post->setVotestotal($post->getVotestotal() + 1);
            }
        }
        $post->setVotesSpread($post->getDownvotes() + $post->getUpvotes());

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
