<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use App\Repository\PollOptionChoiceRepository;
use App\Repository\PostFeedbackRepository;
use App\Repository\PostRepository;
use App\Repository\VoteRepository;
use App\Service\PollOptionChoiceService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DeletePostSubscriber implements EventSubscriberInterface
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var VoteRepository
     */
    private VoteRepository $votesRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var PollOptionChoiceService
     */
    private PollOptionChoiceService $choiceService;

    /**
     * @var PollOptionChoiceRepository
     */
    private PollOptionChoiceRepository $pollOptionChoiceRepository;

    /**
     * @var PostFeedbackRepository
     */
    private PostFeedbackRepository $postFeedbackRepository;

    public function __construct
    (
        PostRepository             $postRepository,
        VoteRepository             $votesRepository,
        EntityManagerInterface     $entityManager,
        PollOptionChoiceService    $choiceService,
        PollOptionChoiceRepository $pollOptionChoiceRepository,
        PostFeedbackRepository     $postFeedbackRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->votesRepository = $votesRepository;
        $this->entityManager = $entityManager;
        $this->choiceService = $choiceService;
        $this->pollOptionChoiceRepository = $pollOptionChoiceRepository;
        $this->postFeedbackRepository = $postFeedbackRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onDeletePostAction', EventPriorities::PRE_WRITE]
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onDeletePostAction(ViewEvent $event)
    {
        $post = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (!$post instanceof Post || Request::METHOD_DELETE !== $method) {
            return;
        }

        if ($post->getParent()) {
            $parent = $this->postRepository->find($post->getParent()->getId());
            $parent->setCommentCount($parent->getCommentCount() - 1);
            $this->entityManager->persist($parent);
            $this->entityManager->flush();
        } else {
            $votes = $this->votesRepository->findBy([
                'post' => $post->getId()
            ]);
            foreach ($votes as $vote) {
                $this->votesRepository->remove($vote);
            }

            $choices = $this->choiceService->getAllChoicesByPost($post->getId());
            foreach ($choices as $choice) {
                $this->pollOptionChoiceRepository->remove($choice);
            }

            $postFeedbacks = $this->postFeedbackRepository->findBy([
                'post' => $post->getId()
            ]);
            foreach ($postFeedbacks as $feedback) {
                $this->postFeedbackRepository->remove($feedback);
            }
        }
    }
}