<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;

class AnonymizeUserController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Security $security,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public function __invoke(Request $request)
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            return null;
        }

        $user
            ->setFirstName(md5($user->getFirstName()))
            ->setLastName(md5($user->getLastName()))
            ->setEmail(md5($user->getEmail()))
            ->setUsername(md5($user->getUsername()))
            ->setDescription('')
            ->setRoles([])
            ->setTutorial(0)
            ->setActive(0)
            ->setDeleted(1);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $request->getSession()->invalidate();
        $this->tokenStorage->setToken(null);

        return $user;
    }
}
