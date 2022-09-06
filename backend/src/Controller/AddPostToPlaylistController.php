<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Playlist;
use App\Entity\Post;
use App\Repository\PlaylistRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class AddPostToPlaylistController extends AbstractController
{
    public function __construct(
        PlaylistRepository $playlistRepository,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager,
        Security $security
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function __invoke(int $playlistId, int $postId): Response
    {
        $user = $this->security->getUser();

        if (null === $user) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        $playlist = $this->playlistRepository->findOneBy(['id' => $playlistId]);
        $post = $this->postRepository->findOneBy(['id' => $postId]);

        if (!$playlist instanceof Playlist || !$post instanceof Post) {
            return new Response('Post or playlist doesn\'t exist', Response::HTTP_BAD_REQUEST);
        }

        if ($playlist->getCreator() !== $user) {
            return new Response('', Response::HTTP_UNAUTHORIZED);
        }

        $playlist->addPost($post);
        $this->entityManager->persist($playlist);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
