<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\CmsContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CmsContent>
 *
 * @method CmsContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsContent[]    findAll()
 * @method CmsContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsContent::class);
    }

    public function add(CmsContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CmsContent $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
