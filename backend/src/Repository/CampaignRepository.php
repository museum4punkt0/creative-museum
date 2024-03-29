<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Campaign;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Campaign>
 *
 * @method Campaign|null find($id, $lockMode = null, $lockVersion = null)
 * @method Campaign|null findOneBy(array $criteria, array $orderBy = null)
 * @method Campaign[]    findAll()
 * @method Campaign[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Campaign::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Campaign $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Campaign $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return float|int|mixed|string
     */
    public function getUnnotifiedActive()
    {
        // get campaign where start < today and stopdate > today and active = 1 and notified = 0

        $qb = $this->_em->createQueryBuilder();
        $activeCampaigns = $qb
            ->select('campaign')
            ->from(Campaign::class, 'campaign')
            ->andWhere(
                $qb->expr()->lte('campaign.start', ':now'),
                $qb->expr()->gte('campaign.stop', ':now'),
                $qb->expr()->eq('campaign.active', 1),
                $qb->expr()->eq('campaign.notified', 0)
            )
            ->orderBy('campaign.start', 'ASC')
            ->setParameter('now', new \DateTime(), Types::DATETIME_MUTABLE)
            ->getQuery()
            ->execute();

        return $activeCampaigns;
    }

    public function getNewestActiveCampaign(): ?Campaign
    {
        $qb = $this->_em->createQueryBuilder();
        $newestCampaign = $qb
            ->select('campaign')
            ->from(Campaign::class, 'campaign')
            ->andWhere(
                $qb->expr()->eq('campaign.active', 1),
                $qb->expr()->eq('campaign.closed', 0)
            )
            ->orderBy('campaign.start', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

        return $newestCampaign[0] ?? null;
    }
}
