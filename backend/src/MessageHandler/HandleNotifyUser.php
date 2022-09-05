<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\MessageHandler;

use App\Entity\Awarded;
use App\Entity\Notification;
use App\Enum\NotificationType;
use App\Message\NotifyUserAboutNewAwarded;
use App\Repository\AwardedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyUser implements MessageHandlerInterface
{
    private AwardedRepository $awardedRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        AwardedRepository $awardedRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->awardedRepository = $awardedRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function __invoke(NotifyUserAboutNewAwarded $awarded)
    {
        $awarded = $this->awardedRepository->find($awarded->getAwardedId());

        if (!$awarded) {
            return;
        }

        $this->handleNewAwarded($awarded);
    }

    private function handleNewAwarded(Awarded $awarded): void
    {
        $winnerNotification = new Notification();
        $winnerNotification
            ->setReceiver($awarded->getWinner())
            ->setText("1662033414")
            ->setColor($awarded->getAward()->getCampaign()->getColor())
            ->setSilent(NotificationType::NONE === $awarded->getWinner()->getNotificationSettings());

        $this->entityManager->persist($winnerNotification);

        $giverNotification = new Notification();
        $giverNotification
            ->setReceiver($awarded->getGiver())
            ->setText("Sie haben den Award {$awarded->getAward()->getTitle()} an {$awarded->getWinner()->getUserIdentifier()} vergeben")
            ->setColor($awarded->getAward()->getCampaign()->getColor())
            ->setSilent(NotificationType::NONE === $awarded->getGiver()->getNotificationSettings());
        $this->entityManager->persist($giverNotification);
        $this->entityManager->flush();
    }
}
