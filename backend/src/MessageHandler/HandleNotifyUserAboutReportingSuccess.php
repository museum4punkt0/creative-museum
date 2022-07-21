<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\User;
use App\Enum\NotificationType;
use App\Message\NotifyUserAboutReportingSuccess;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyUserAboutReportingSuccess implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        UserRepository $userRepository,
        PostRepository $postRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyUserAboutReportingSuccess $report)
    {
        $user = $this->userRepository->find($report->getUserId());
        $post = $this->postRepository->find($report->getPostId());

        if (!$user || !$post){
            return;
        }

        $this->handlePostReportedSuccessNotification($user,$post);
    }

    private function handlePostReportedSuccessNotification(User $reporter, Post $post)
    {
        $reporterNotification = new Notification();
        $reporterNotification
            ->setReceiver($reporter)
            ->setPost($post)
            ->setText('Danke, wir kümmern uns und prüfen den Post.')
            ->setSilent($reporter->getNotificationSettings() === NotificationType::NONE);
        $this->entityManager->persist($reporterNotification);

        $postAuthorNotification = new Notification();
        $postAuthorNotification
            ->setReceiver($post->getAuthor())
            ->setPost($post)
            ->setText('Ihr Beitrag wird von der Redaktion auf unangemessene Inhalte geprüft.')
            ->setSilent($post->getAuthor()->getNotificationSettings() === NotificationType::NONE);
        $this->entityManager->persist($postAuthorNotification);
        $this->entityManager->flush();
    }
}