<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;


class SetBookmarkController extends AbstractController
{
    private Security $security;

    private EntityManagerInterface $entityManager;

    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function __invoke($id, Post $data): Post
    {
        /** @var User $user */
        $user = $this->security->getUser();

         $exists = $user->getBookmarks()->contains($data);

         if ($exists) {
             $user->getBookmarks()->removeElement($data);
         } else {
             $user->getBookmarks()->add($data);
         }

         $this->entityManager->persist($user);
         $this->entityManager->flush();

        return $data;
    }
}