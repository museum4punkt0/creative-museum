<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Campaign;
use App\Entity\CampaignMember;
use App\Entity\Notification;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CampaignDeleteListener
{
    public function __construct
    (
        private readonly EntityManagerInterface $entityManager
    ){}

    public function preRemove(Campaign $campaign, LifecycleEventArgs $event): void
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(Notification::class, 'n')
            ->where('n.campaign = :campaignId')
            ->setParameter('campaignId', $campaign->getId())
            ->getQuery()
            ->execute();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->delete(CampaignMember::class, 'n')
            ->where('n.campaign = :campaignId')
            ->setParameter('campaignId', $campaign->getId())
            ->getQuery()
            ->execute();
    }
}