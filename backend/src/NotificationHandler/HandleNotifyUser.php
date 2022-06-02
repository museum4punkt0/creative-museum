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
        $notification = new Notification();
        $notification
            ->setReceiver($awarded->getWinner())
            ->setText("Du hast den Award {$awarded->getAward()->getTitle()} von {$awarded->getGiver()->getUserIdentifier()} erhalten");
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}