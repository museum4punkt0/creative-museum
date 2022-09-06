<?php

namespace App\MessageHandler;

use App\Entity\Awarded;
use App\Entity\Notification;
use App\Enum\NotificationType;
use App\Message\NotifyAboutNewAwarded;
use App\Repository\AwardedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutNewAwarded implements MessageHandlerInterface
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
    public function __invoke(NotifyAboutNewAwarded $awarded)
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
            ->setAwardGiver($awarded->getGiver())
            ->setText("1662033414")
            ->setColor($awarded->getAward()->getCampaign()->getColor())
            ->setSilent(NotificationType::NONE === $awarded->getWinner()->getNotificationSettings())
            ->setAward($awarded->getAward());

        $this->entityManager->persist($winnerNotification);

        $giverNotification = new Notification();
        $giverNotification
            ->setReceiver($awarded->getGiver())
            ->setAwardWinner($awarded->getWinner())
            ->setText("1662114293")
            ->setColor($awarded->getAward()->getCampaign()->getColor())
            ->setSilent(NotificationType::NONE === $awarded->getGiver()->getNotificationSettings())
            ->setAward($awarded->getAward());
        $this->entityManager->persist($giverNotification);
        $this->entityManager->flush();
    }
}