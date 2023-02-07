<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\CampaignMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CampaignMember>
 *
 * @method CampaignMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method CampaignMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method CampaignMember[]    findAll()
 * @method CampaignMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignMemberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampaignMember::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CampaignMember $entity, bool $flush = true): void
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
    public function remove(CampaignMember $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getCampaignLeaders(int $campaignId)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb
            ->select('members')
            ->from(CampaignMember::class, 'members')
            ->andWhere(
                $qb->expr()->eq('members.campaign', $campaignId)
            )
            ->orderBy('members.rewardPoints', 'DESC')
            ->getQuery()
            ->execute();
    }
}
