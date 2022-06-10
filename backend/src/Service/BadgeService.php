<?php

namespace App\Service;

use App\Entity\Badge;
use App\Entity\Badged;
use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\BadgedRepository;
use App\Repository\CampaignMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class BadgeService
{
    /**
     * @var CampaignMemberRepository
     */
    private CampaignMemberRepository $campaignMemberRepository;

    /**
     * @var BadgedRepository
     */
    private BadgedRepository $badgedRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(
        CampaignMemberRepository $campaignMemberRepository,
        BadgedRepository         $badgedRepository,
        EntityManagerInterface   $em
    )
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->badgedRepository = $badgedRepository;
        $this->em = $em;
    }

    /**
     * @param Campaign $campaign
     * @param User $user
     * @return Badge|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNextHigherBadge(Campaign $campaign, User $user): ?Badge
    {
        $qb = $this->em->createQueryBuilder();
        $nextBadge = $qb->select('badge')
            ->from(Badge::class, 'badge')
            ->andWhere(
                $qb->expr()->eq('badge.campaign', $campaign->getId()),
                $qb->expr()->isNull('badged.id')
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
            ->orderBy('badge.price','ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $nextBadge;
    }
}