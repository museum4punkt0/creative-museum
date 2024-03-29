<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Badged;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Badged>
 *
 * @method Badged|null find($id, $lockMode = null, $lockVersion = null)
 * @method Badged|null findOneBy(array $criteria, array $orderBy = null)
 * @method Badged[]    findAll()
 * @method Badged[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BadgedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Badged::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Badged $entity, bool $flush = true): void
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
    public function remove(Badged $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
