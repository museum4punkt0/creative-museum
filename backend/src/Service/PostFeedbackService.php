<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\CampaignFeedbackOption;
use App\Entity\Post;
use App\Entity\PostFeedback;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class PostFeedbackService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFeedbackResultsForPost(int $postId): array
    {
        $qb = $this->em->createQueryBuilder();

        return $qb->select('option.id')
            ->addSelect('count(option.id) as amount')
            ->from(PostFeedback::class, 'feedback')
            ->where($qb->expr()->eq('feedback.post', $postId))
            ->innerJoin(
                CampaignFeedbackOption::class,
                'option',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('option.id', 'feedback.selection')
                )
            )
            ->groupBy('option.id')
            ->getQuery()
            ->execute();
    }

    /**
     * @param int $postId
     */
    public function getGivenFeedbackByPostAndUser(Post $post, int $userId): array
    {
        $qb = $this->em->createQueryBuilder();
        $givenFeedback = $qb->select('feedback')
            ->from(PostFeedback::class, 'feedback')
            ->andWhere(
                $qb->expr()->in('option.id', 'feedback.selection'),
                $qb->expr()->eq('feedback.user', $userId),
                $qb->expr()->eq('feedback.post', $post->getId())
            )
            ->leftJoin(
                CampaignFeedbackOption::class,
                'option',
                Expr\Join::WITH,
                $qb->expr()->andX(
                    $qb->expr()->eq('option.campaign', $post->getCampaign()->getId())
                )
            )
            ->getQuery()
            ->execute();

        return $givenFeedback;
    }
}
