<?php

namespace App\EventListener;

use App\Entity\Awarded;
use App\Entity\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class UserDeleteListener
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface $tokenStorage,
        private readonly RequestStack $requestStack
    ){}

    public function preRemove(User $user, LifecycleEventArgs $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $request->getSession()->invalidate();
        $this->tokenStorage->setToken(null);

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder->update(Notification::class, 'notification')
            ->set('notification.awardGiver', ':giver')
            ->where('notification.awardGiver = :userId')
            ->setParameter('giver', null)
            ->setParameter('userId', $user->getId())
            ->getQuery();
        $query->execute();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(Notification::class, 'n')
            ->where('n.receiver = :userId')
            ->orWhere('n.awardWinner = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->execute();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $query = $queryBuilder->update(Awarded::class, 'awarded')
            ->set('awarded.giver', ':giver')
            ->where('awarded.giver = :userId')
            ->setParameter('giver', null)
            ->setParameter('userId', $user->getId())
            ->getQuery();
        $query->execute();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(Awarded::class, 'n')
            ->where('n.winner = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->execute();
    }
}