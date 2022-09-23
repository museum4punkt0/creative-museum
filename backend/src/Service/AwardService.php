<?php

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

    public function getUnawarded(Campaign $campaign, User $user)
    {
        $qb = $this->entityManager->createQueryBuilder();
        $unawardedArr = $qb->select('award')
            ->from(Award::class, 'award')
            ->andWhere(
                $qb->expr()->eq('award.campaign', $campaign->getId()),
                $qb->expr()->isNull('awarded.id'),
                $qb->expr()->gte('membership.score','award.price')
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
            ->leftJoin(
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

        return $unawardedArr;
    }
}