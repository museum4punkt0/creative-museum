<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Award;
use App\Entity\Awarded;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class AwardDeleteListener
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function preRemove(Award $award, LifecycleEventArgs $event): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder->delete(Notification::class, 'n')
            ->where('n.award = :awardId')
            ->setParameter('awardId', $award->getId())
            ->getQuery();
        $query->execute();

        $query = $queryBuilder->delete(Awarded::class, 'b')
            ->where('b.award = :awardId')
            ->setParameter('awardId', $award->getId())
            ->getQuery();
        $query->execute();
    }
}