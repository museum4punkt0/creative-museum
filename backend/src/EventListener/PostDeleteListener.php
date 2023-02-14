<?php

namespace App\EventListener;

use App\Entity\Post;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PostDeleteListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly NotificationRepository $notificationRepository
    ){}

    public function preRemove(Post $post, LifecycleEventArgs $event): void
    {
        $postNotifications = $this->notificationRepository->findBy(['post' => $post->getId()]);

        foreach ($postNotifications as $notification) {
            $this->entityManager->remove($notification);
        }
        $this->entityManager->flush();

        if ($post->getParent()) {
            $parent = $post->getParent();
            $parent->setCommentCount($parent->getCommentCount() - 1);
            $this->entityManager->persist($parent);
            $this->entityManager->flush();
        } else {
            $pollOptions = $post->getPollOptions();
            foreach ($pollOptions as $pollOption) {
                $this->entityManager->remove($pollOption);
            }
            $this->entityManager->flush();

            $posts = $post->getComments();
            $post->setLeadingFeedbackOption(null);
            $this->entityManager->persist($post);
            foreach ($posts as $post) {
                $this->entityManager->remove($post);
            }
            $this->entityManager->flush();
        }
    }
}