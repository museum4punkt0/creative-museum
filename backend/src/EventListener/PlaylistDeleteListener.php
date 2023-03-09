<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Entity\Playlist;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PlaylistDeleteListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PostRepository $postRepository
    ) {
    }

    public function preRemove(Playlist $playlist, LifecycleEventArgs $event): void
    {
        $posts = $this->postRepository->findBy(['linkedPlaylist' => $playlist->getId()]);

        foreach ($posts as $post) {
            $this->entityManager->remove($post);
        }
        $this->entityManager->flush();
    }
}
