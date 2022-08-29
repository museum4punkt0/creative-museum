<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Event\FeedbackCreatedEvent;
use App\Repository\PostFeedbackRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DeterminePrimaryFeedbackSubscriber implements EventSubscriberInterface
{
    private PostFeedbackRepository $feedbackRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        PostFeedbackRepository $feedbackRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->feedbackRepository = $feedbackRepository;
        $this->entityManager = $entityManager;
    }

    #[ArrayShape([FeedbackCreatedEvent::NAME => 'string'])]
    public static function getSubscribedEvents(): array
    {
        return [
            FeedbackCreatedEvent::NAME => 'onFeedbackCreated',
        ];
    }

    public function onFeedbackCreated(FeedbackCreatedEvent $feedbackCreatedEvent): void
    {
        $post = $feedbackCreatedEvent->getPostFeedback()->getPost();

        $feedbacks = $this->feedbackRepository->findBy(['post' => $post]);

        $calc = [];

        foreach ($feedbacks as $feedback) {
            if (!isset($calc[$feedback->getId()])) {
                $calc[$feedback->getId()] = ['feedback' => $feedback->getSelection(), 'count' => 1];
                continue;
            }
            ++$calc[$feedback->getId()]['count'];
        }

        uasort($calc, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $leading = reset($calc);

        $post->setLeadingFeedbackOption($leading['feedback']);
        $post->setLeadingFeedbackCount($leading['count']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
