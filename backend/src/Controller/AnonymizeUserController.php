<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnonymizeUserController extends AbstractController
{
    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct
    (
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke($userId)
    {
        $user = $this->userRepository->find($userId);

        if (!$user instanceof User){
            return;
        }

        $user->setFirstName(md5($user->getFirstName()));
        $user->setLastName(md5($user->getLastName()));
        $user->setFullName(md5($user->getFullName()));
        $user->setEmail(md5($user->getEmail()));
        $user->setActive(0);
        $user->setDeleted(1);
        $user->setDescription('');
    }
}