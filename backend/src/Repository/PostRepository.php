<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Post $entity, bool $flush = true): void
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
    public function remove(Post $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return void
     */
    public function increaseCommentCount(Post $comment)
    {
        $query = $this->_em->createQuery(
            "UPDATE App\Entity\Post p
            SET p.commentCount = p.commentCount + 1
            WHERE p.id = {$comment->getParent()->getId()}"
        );
        $query->execute();
    }

    /**
     * @param Post $post
     *
     * @return float|int|mixed|string
     */
    public function getRecentPostComments(int $postId, int $limit = 2)
    {
        $qb = $this->_em->createQueryBuilder();
        $comments = $qb
            ->select('post')
            ->from(Post::class, 'post')
            ->andWhere(
                $qb->expr()->eq('post.parent', $postId),
            )
            ->orderBy('post.created', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->execute();

        return $comments;
    }
}
