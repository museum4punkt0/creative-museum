<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Badge;
use App\Entity\Badged;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class BadgeDeleteListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function preRemove(Badge $badge, LifecycleEventArgs $event): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder->delete(Notification::class, 'n')
            ->where('n.badge = :badgeId')
            ->setParameter('badgeId', $badge->getId())
            ->getQuery();
        $query->execute();

        $query = $queryBuilder->delete(Badged::class, 'b')
            ->where('b.badge = :badgeId')
            ->setParameter('badgeId', $badge->getId())
            ->getQuery();
        $query->execute();
    }
}