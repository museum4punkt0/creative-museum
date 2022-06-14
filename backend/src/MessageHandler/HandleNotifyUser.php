<?php

namespace App\MessageHandler;

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

    /**
     * @param NotifyUserAboutNewAwarded $awarded
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

    /**
     * @param Awarded $awarded
     * @return void
     */
    private function handleNewAwarded(Awarded $awarded): void
    {
        $winnerNotification = new Notification();
        $winnerNotification
            ->setReceiver($awarded->getWinner())
            ->setText("Sie haben den Award {$awarded->getAward()->getTitle()} von {$awarded->getGiver()->getUserIdentifier()} erhalten")
            ->setColor($awarded->getAward()->getCampaign()->getColor());
        $this->entityManager->persist($winnerNotification);

        $giverNotification = new Notification();
        $giverNotification
            ->setReceiver($awarded->getGiver())
            ->setText("Sie haben den Award {$awarded->getAward()->getTitle()} an {$awarded->getWinner()->getUserIdentifier()} vergeben")
            ->setColor($awarded->getAward()->getCampaign()->getColor());
        $this->entityManager->persist($giverNotification);
        $this->entityManager->flush();
    }
}