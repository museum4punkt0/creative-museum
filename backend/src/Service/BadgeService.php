<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Badge;
use App\Entity\Badged;
use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\BadgedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class BadgeService
{
    private BadgedRepository $badgedRepository;

    private EntityManagerInterface $em;

    public function __construct(
        BadgedRepository       $badgedRepository,
        EntityManagerInterface $em
    )
    {
        $this->badgedRepository = $badgedRepository;
        $this->em = $em;
    }

    /**
     * Returns all badges which are not badged to the user by campaign and user.
     */
    public function getUnbadged(Campaign $campaign, User $user): array
    {
        $qb = $this->em->createQueryBuilder();
        $unbadgedArr = $qb->select('badge')
            ->from(Badge::class, 'badge')
            ->andWhere(
                $qb->expr()->eq('badge.campaign', $campaign->getId()),
                $qb->expr()->isNull('badged.id'),
            )
            ->leftJoin(
                Badged::class,
                'badged',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('badged.badge', 'badge.id'),
                    $qb->expr()->eq('badged.user', $user->getId())
                )
            )
            ->getQuery()
            ->execute();

        return $unbadgedArr;
    }

    public function createBadged(Badge $badge, User $user): Badged
    {
        $newBadged = new Badged();
        $newBadged
            ->setBadge($badge)
            ->setUser($user);
        $this->badgedRepository->add($newBadged);
        $this->em->flush();

        return $newBadged;
    }

    public function getLastBadge(int $userId): ?Badge
    {
        $badges = $this->badgedRepository->findBy([
            'user' => $userId
        ]);
        $lastBadged = end($badges);

        if ($lastBadged){
            return $lastBadged->getBadge();
        }else{
            return null;
        }
    }
}
