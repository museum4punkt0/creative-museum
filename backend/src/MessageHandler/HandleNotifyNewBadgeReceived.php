<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\MessageHandler;

use App\Entity\Badged;
use App\Entity\Notification;
use App\Enum\NotificationType;
use App\Message\NotifyNewBadgeReceived;
use App\Repository\BadgedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyNewBadgeReceived implements MessageHandlerInterface
{
    private BadgedRepository $badgedRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(BadgedRepository $badgedRepository, EntityManagerInterface $entityManager)
    {
        $this->badgedRepository = $badgedRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyNewBadgeReceived $notifyNewBadgeReceived)
    {
        $badged = $this->badgedRepository->findOneBy(
            [
                'user' => $notifyNewBadgeReceived->getUserId(),
                'badge' => $notifyNewBadgeReceived->getBadgeId(),
            ]
        );

        if (!$badged) {
            return;
        }
        $this->handleNewBadgeReceivedNotification($badged);
    }

    private function handleNewBadgeReceivedNotification(Badged $badged)
    {
        $notification = new Notification();
        $notification
            ->setReceiver($badged->getUser())
            ->setText("1662033328")
            ->setSilent(NotificationType::NONE === $badged->getUser()->getNotificationSettings())
            ->setCampaign($badged->getBadge()->getCampaign())
            ->setBadge($badged->getBadge());
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}
