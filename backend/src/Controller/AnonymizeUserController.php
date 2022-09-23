<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class AnonymizeUserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private Security $security;

    private TokenStorageInterface $tokenStorage;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        Security               $security,
        TokenStorageInterface $tokenStorage
    )
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
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