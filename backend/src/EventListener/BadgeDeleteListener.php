<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
    ) {
    }

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
