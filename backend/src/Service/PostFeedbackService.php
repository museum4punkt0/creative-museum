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
use App\Repository\PostFeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr;

class PostFeedbackService
{
    public function __construct(
        private EntityManagerInterface $em,
        private readonly PostFeedbackRepository $postFeedbackRepository
    ) {
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

    /**
     * @param CampaignFeedbackOption|null $excludedOption
     *                                                    The option to be excluded from the calculation @see CampaignFeedbackOptionDeleteListener
     */
    public function getLeadingFeedbackWithCount(Post $post, ?CampaignFeedbackOption $excludedOption = null): ?array
    {
        $feedbacks = $this->postFeedbackRepository->findBy(['post' => $post]);

        $calc = [];

        foreach ($feedbacks as $feedback) {
            if (null !== $excludedOption && $feedback->getSelection() === $excludedOption) {
                continue;
            }
            if (!isset($calc[$feedback->getId()])) {
                $calc[$feedback->getId()] = ['feedback' => $feedback->getSelection(), 'count' => 1];
                continue;
            }
            ++$calc[$feedback->getId()]['count'];
        }

        if (count($calc) > 1) {
            uasort($calc, function ($a, $b) {
                return $b['count'] <=> $a['count'];
            });
        }

        return reset($calc) ?: null;
    }
}
