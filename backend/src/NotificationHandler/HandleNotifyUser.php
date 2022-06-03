<?php

namespace App\NotificationHandler;

use App\Entity\Awarded;
use App\Entity\Notification;
use App\Message\NotifyUserAboutNewAwarded;
use App\Repository\AwardedRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\Messenger\Handler\MessageHandlerInterface;


class HandleNotifyUser implements MessageHandlerInterface
{
    private AwardedRepository $awardedRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        AwardedRepository      $awardedRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->awardedRepository = $awardedRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyUserAboutNewAwarded $awarded)
    {
        $awarded = $this->awardedRepository->find($awarded->getAwardedId());

        if (!$awarded) {
            return;
        }

        $this->handleNewAwarded($awarded);
    }

    private function handleNewAwarded(Awarded $awarded)
    {
        $winnerNotification = new Notification();
        $winnerNotification
            ->setReceiver($awarded->getWinner())
            ->setText("Sie haben den Award {$awarded->getAward()->getTitle()} von {$awarded->getGiver()->getUserIdentifier()} erhalten");
        $this->entityManager->persist($winnerNotification);

        $giverNotification = new Notification();
        $giverNotification
            ->setReceiver($awarded->getGiver())
            ->setText("Sie haben den Award {$awarded->getAward()->getTitle()} an {$awarded->getWinner()->getUserIdentifier()} vergeben");
        $this->entityManager->persist($giverNotification);
        $this->entityManager->flush();
    }
}