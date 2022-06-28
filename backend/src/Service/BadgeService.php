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
     * Returns all badges which are not badged to the user by campaign and user
     *
     * @param Campaign $campaign
     * @param User $user
     * @return array
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

    /**
     * @param Badge $badge
     * @param User $user
     * @return void
     */
    public function createBadged(Badge $badge, User $user): void
    {
        $newBadged = new Badged();
        $newBadged
            ->setBadge($badge)
            ->setUser($user);
        $this->badgedRepository->add($newBadged);
    }
}