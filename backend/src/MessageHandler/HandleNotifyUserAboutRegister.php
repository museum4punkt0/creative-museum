<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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

        if (!$user) {
            return;
        }
        $this->handleNewUserRegisteredNotification($user);
    }

    private function handleNewUserRegisteredNotification(User $user): void
    {
        $notification = new Notification();
        $notification
            ->setReceiver($user)
            ->setText('1662033487')
            ->setSilent(NotificationType::NONE === $user->getNotificationSettings());
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
