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

    private function handlePostReportedSuccessNotification(User $user, Post $post)
    {
        $notification = new Notification();
        $notification
            ->setReceiver($user)
            ->setPost($post)
            ->setText('Danke, wir kümmern uns und prüfen den Post.')
            ->setSilent($user->getNotificationSettings() === NotificationType::NONE);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}