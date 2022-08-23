<?php

namespace App\MessageHandler;

use App\Entity\Campaign;
use App\Entity\Notification;
use App\Entity\User;
use App\Enum\NotificationType;
use App\Message\NotifyAboutAwardReceivedPoints;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutAwardReceivedPoints implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var CampaignRepository
     */
    private CampaignRepository $campaignRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        CampaignRepository $campaignRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->campaignRepository = $campaignRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyAboutAwardReceivedPoints $awardReceivedPointsMessage)
    {
        $receiver = $this->userRepository->find($awardReceivedPointsMessage->getUserId());
        $campaign = $this->campaignRepository->find($awardReceivedPointsMessage->getCampaignId());
        $points = $awardReceivedPointsMessage->getPoints();

        if (!$receiver || !$campaign){
            return;
        }

        $this->notifyAboutAwardReceivedPoints($receiver, $campaign, $points);
    }

    /**
     * @param User $receiver
     * @return void
     */
    private function notifyAboutAwardReceivedPoints(User $receiver, Campaign $campaign, int $points): void
    {
        $pointsNotification = new Notification();

        $pointsNotification
            ->setReceiver($receiver)
            ->setText("Du hast {$points} Punkte erhalten, weil du einen Award in der Kampange {$campaign->getTitle()} erhalten hast!")
            ->setCampaign($campaign)
            ->setSilent($receiver->getNotificationSettings() === NotificationType::NONE)
            ->setColor($campaign->getColor())
            ->setScorePoints($points);

        $this->entityManager->persist($pointsNotification);
        $this->entityManager->flush();
    }
}