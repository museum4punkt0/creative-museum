<?php
declare(strict_types=1);

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

    /**
     * @param PostFeedbackRepository $feedbackRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        PostFeedbackRepository $feedbackRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->feedbackRepository = $feedbackRepository;
        $this->entityManager = $entityManager;
    }

    #[ArrayShape([FeedbackCreatedEvent::NAME => "string"])]
    public static function getSubscribedEvents(): array
    {
        return [
            FeedbackCreatedEvent::NAME => 'onFeedbackCreated',
        ];
    }

    /**
     * @param FeedbackCreatedEvent $feedbackCreatedEvent
     * @return void
     */
    public function onFeedbackCreated(FeedbackCreatedEvent $feedbackCreatedEvent): void
    {
        $post = $feedbackCreatedEvent->getPostFeedback()->getPost();

        $feedbacks = $this->feedbackRepository->findBy(['post' => $post]);

        $calc = [];

        foreach ($feedbacks as $feedback) {
            if (!isset ($calc[$feedback->getId()])) {
                $calc[$feedback->getId()] = ['feedback' => $feedback->getSelection(), 'count' => 1];
                continue;
            }
            $calc[$feedback->getId()]['count'] += 1;
        }

        uasort($calc, function($a, $b) {
            return $b['count'] <=> $a['count'];
        });

        $leading = reset($calc);

        $post->setLeadingFeedbackOption($leading['feedback']);
        $post->setLeadingFeedbackCount($leading['count']);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}