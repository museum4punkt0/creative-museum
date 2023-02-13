<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Awarded;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Awarded>
 *
 * @method Awarded|null find($id, $lockMode = null, $lockVersion = null)
 * @method Awarded|null findOneBy(array $criteria, array $orderBy = null)
 * @method Awarded[]    findAll()
 * @method Awarded[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AwardedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Awarded::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Awarded $entity, bool $flush = true): void
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
    public function remove(Awarded $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
