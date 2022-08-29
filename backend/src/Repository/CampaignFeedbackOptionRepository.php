<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\CampaignFeedbackOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CampaignFeedbackOption>
 *
 * @method CampaignFeedbackOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method CampaignFeedbackOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method CampaignFeedbackOption[]    findAll()
 * @method CampaignFeedbackOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CampaignFeedbackOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CampaignFeedbackOption::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(CampaignFeedbackOption $entity, bool $flush = true): void
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
    public function remove(CampaignFeedbackOption $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return CampaignFeedbackOption[] Returns an array of CampaignFeedbackOption objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CampaignFeedbackOption
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
