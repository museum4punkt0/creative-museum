<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventListener;

use App\Entity\CampaignFeedbackOption;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\PostFeedbackService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CampaignFeedbackOptionDeleteListener
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly PostFeedbackService $postFeedbackService,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function preRemove(CampaignFeedbackOption $campaignFeedbackOption, LifecycleEventArgs $event): void
    {
        $feedbacks = $campaignFeedbackOption->getVotes();
        foreach ($feedbacks as $feedback) {
            $this->entityManager->remove($feedback);
        }
        $this->entityManager->flush();

        $posts = $this->postRepository->findBy(['leadingFeedbackOption' => $campaignFeedbackOption]);

        foreach ($posts as $post) {
            $leading = $this->postFeedbackService->getLeadingFeedbackWithCount($post, $campaignFeedbackOption);

            $queryBuilder = $this->entityManager->createQueryBuilder();
            $query = $queryBuilder->update(Post::class, 'post')
                ->set('post.leadingFeedbackOption', ':option')
                ->set('post.leadingFeedbackCount', ':count')
                ->where('post.id = :postId')
                ->setParameter('option', !empty($leading) ? $leading['feedback']->getId() : null)
                ->setParameter('count', !empty($leading) ? $leading['count'] : 0)
                ->setParameter('postId', $post->getId())
                ->getQuery();
            $query->execute();
        }
    }
}
