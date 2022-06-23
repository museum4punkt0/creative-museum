<?php

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Entity\User;
use App\Enum\NotificationType;
use App\Message\NotifyUserAboutRegister;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyUserAboutRegister implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyUserAboutRegister $user)
    {
        $user = $this->userRepository->find($user->getUserId());

        if (!$user){
            return;
        }
        $this->handleNewUserRegisteredNotification($user);
    }

    /**
     * @param User $user
     * @return void
     */
    private function handleNewUserRegisteredNotification(User $user): void
    {
        $notification = new Notification();
        $notification
            ->setReceiver($user)
            ->setText('Herzlich Willkommen im Creative Museum')
            ->setSilent($user->getNotificationSettings() === NotificationType::NONE);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}