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
use App\Service\PostFeedbackService;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DeterminePrimaryFeedbackSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PostFeedbackService $postFeedbackService
    ) {
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

        $leading = $this->postFeedbackService->getLeadingFeedbackWithCount($post);

        $post->setLeadingFeedbackOption($leading['feedback']);
        $post->setLeadingFeedbackCount($leading['count']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}
