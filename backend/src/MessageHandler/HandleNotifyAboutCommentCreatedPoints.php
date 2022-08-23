<?php

namespace App\MessageHandler;

use App\Entity\Campaign;
use App\Entity\Notification;
use App\Entity\User;
use App\Enum\NotificationType;
use App\Message\NotifyAboutCommentCreatedPoints;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutCommentCreatedPoints implements MessageHandlerInterface
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

    public function __invoke(NotifyAboutCommentCreatedPoints $commentCreatedPointsMessage)
    {
        $receiver = $this->userRepository->find($commentCreatedPointsMessage->getUserId());
        $campaign = $this->campaignRepository->find($commentCreatedPointsMessage->getCampaignId());
        $points = $commentCreatedPointsMessage->getPoints();

        if (!$receiver || !$campaign){
            return;
        }

        $this->notifyAboutCommentCreatedPoints($receiver, $campaign, $points);
    }

    /**
     * @param User $receiver
     * @return void
     */
    private function notifyAboutCommentCreatedPoints(User $receiver, Campaign $campaign, int $points): void
    {
        $pointsNotification = new Notification();

        $pointsNotification
            ->setReceiver($receiver)
            ->setText("Du hast {$points} Punkte für dein Kommentar in der Kampange {$campaign->getTitle()} erhalten!")
            ->setCampaign($campaign)
            ->setSilent($receiver->getNotificationSettings() === NotificationType::NONE)
            ->setColor($campaign->getColor())
            ->setScorePoints($points);

        $this->entityManager->persist($pointsNotification);
        $this->entityManager->flush();
    }
}