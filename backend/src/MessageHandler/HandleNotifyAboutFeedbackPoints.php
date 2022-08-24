<?php

namespace App\MessageHandler;

use App\Entity\Campaign;
use App\Entity\Notification;
use App\Entity\User;
use App\Enum\NotificationType;
use App\Message\NotifyAboutFeedbackPoints;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutFeedbackPoints implements MessageHandlerInterface
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

    public function __invoke(NotifyAboutFeedbackPoints $feedbackPointsMessage)
    {
        $receiver = $this->userRepository->find($feedbackPointsMessage->getUserId());
        $campaign = $this->campaignRepository->find($feedbackPointsMessage->getCampaignId());
        $points = $feedbackPointsMessage->getPoints();

        if (!$receiver || !$campaign){
            return;
        }

        $this->notifyAboutFeedbackPoints($receiver, $campaign, $points);
    }

    /**
     * @param User $receiver
     * @return void
     */
    private function notifyAboutFeedbackPoints(User $receiver, Campaign $campaign, int $points): void
    {
        $pointsNotification = new Notification();

        $pointsNotification
            ->setReceiver($receiver)
            ->setText("Du hast {$points} Punkte für dein Feedback in der Kampange {$campaign->getTitle()} erhalten!")
            ->setCampaign($campaign)
            ->setSilent($receiver->getNotificationSettings() === NotificationType::NONE)
            ->setColor($campaign->getColor())
            ->setScorePoints($points);

        $this->entityManager->persist($pointsNotification);
        $this->entityManager->flush();
    }
}