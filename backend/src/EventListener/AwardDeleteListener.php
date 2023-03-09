<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
    ) {
    }

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
