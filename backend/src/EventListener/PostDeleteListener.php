<?php

namespace App\EventListener;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class PostDeleteListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ){}

    public function preRemove(Post $post, LifecycleEventArgs $event): void
    {
        if ($post->getParent()) {
            $parent = $post->getParent();
            $parent->setCommentCount($parent->getCommentCount() - 1);
            $this->entityManager->persist($parent);
            $this->entityManager->flush();
        } else {
            $posts = $post->getComments();
            foreach ($posts as $post) {
                $this->entityManager->remove($post);
            }
            $this->entityManager->flush();
        }
    }
}