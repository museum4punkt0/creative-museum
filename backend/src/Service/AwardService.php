<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Award;
use App\Entity\Awarded;
use App\Entity\Campaign;
use App\Entity\CampaignMember;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class AwardService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAvailableByCampaign(Campaign $campaign, User $user)
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('award')
            ->from(Award::class, 'award')
            ->andWhere(
                $qb->expr()->eq('award.campaign', $campaign->getId()),
                $qb->expr()->isNull('awarded.id'),
                $qb->expr()->gte('membership.score', 'award.price')
            )
            ->join(
                CampaignMember::class,
                'membership',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('membership.user', $user->getId()),
                    $qb->expr()->eq('membership.campaign', $campaign->getId())
                )
            )
            ->leftjoin(
                Awarded::class,
                'awarded',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('awarded.award', 'award.id'),
                    $qb->expr()->eq('awarded.giver', $user->getId())
                )
            )
            ->getQuery()
            ->execute();
    }

    public function getAllAvailable(User $user)
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('award')
            ->from(Award::class, 'award')
            ->andWhere(
                $qb->expr()->isNull('awarded.id'),
                $qb->expr()->gte('membership.score', 'award.price'),
                $qb->expr()->eq('campaign.active', 1),
                $qb->expr()->eq('campaign.closed', 0),
                $qb->expr()->eq('campaign.published', 1)
            )
            ->join(
                CampaignMember::class,
                'membership',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('membership.user', $user->getId()),
                )
            )
            ->leftjoin(
                Awarded::class,
                'awarded',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('awarded.award', 'award.id'),
                    $qb->expr()->eq('awarded.giver', $user->getId())
                )
            )
            ->leftJoin(
                Campaign::class,
                'campaign',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('campaign.id', 'award.campaign'),
                )
            )
            ->getQuery()
            ->execute();
    }

    public function getAvailableSoonByCampaign(Campaign $campaign, User $user)
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('award')
            ->from(Award::class, 'award')
            ->andWhere(
                $qb->expr()->eq('award.campaign', $campaign->getId()),
                $qb->expr()->lt('membership.score', 'award.price'),
                $qb->expr()->isNull('awarded.id')
            )
            ->join(
                CampaignMember::class,
                'membership',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('membership.user', $user->getId()),
                    $qb->expr()->eq('membership.campaign', $campaign->getId())
                )
            )
            ->leftjoin(
                Awarded::class,
                'awarded',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('awarded.award', 'award.id'),
                    $qb->expr()->eq('awarded.giver', $user->getId())
                )
            )
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    public function getAllAvailableSoon(User $user)
    {
        $qb = $this->entityManager->createQueryBuilder();

        return $qb->select('award')
            ->from(Award::class, 'award')
            ->andWhere(
                $qb->expr()->lt('membership.score', 'award.price'),
                $qb->expr()->eq('campaign.active', 1),
                $qb->expr()->eq('campaign.closed', 0),
                $qb->expr()->eq('campaign.published', 1),
                $qb->expr()->isNull('awarded.id')
            )
            ->join(
                CampaignMember::class,
                'membership',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('membership.user', $user->getId()),
                )
            )
            ->leftjoin(
                Awarded::class,
                'awarded',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('awarded.award', 'award.id'),
                    $qb->expr()->eq('awarded.giver', $user->getId())
                )
            )
            ->leftJoin(
                Campaign::class,
                'campaign',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('campaign.id', 'award.campaign'),
                )
            )
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }
}
