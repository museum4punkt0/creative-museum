<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\MessageHandler;

use App\Entity\Campaign;
use App\Entity\Notification;
use App\Entity\User;
use App\Enum\NotificationType;
use App\Message\NotifyAboutUpvotePoints;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutUpvotePoints implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    private CampaignRepository $campaignRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        CampaignRepository $campaignRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->campaignRepository = $campaignRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyAboutUpvotePoints $upvotePointsMessage)
    {
        $receiver = $this->userRepository->find($upvotePointsMessage->getUserId());
        $campaign = $this->campaignRepository->find($upvotePointsMessage->getCampaignId());
        $points = $upvotePointsMessage->getPoints();

        if (!$receiver || !$campaign) {
            return;
        }

        $this->notifyAboutUpvotePoints($receiver, $campaign, $points);
    }

    private function notifyAboutUpvotePoints(User $receiver, Campaign $campaign, int $points): void
    {
        $pointsNotification = new Notification();

        $pointsNotification
            ->setReceiver($receiver)
            ->setText("1662033222")
            ->setCampaign($campaign)
            ->setSilent(NotificationType::NONE === $receiver->getNotificationSettings())
            ->setColor($campaign->getColor())
            ->setScorePoints($points);

        $this->entityManager->persist($pointsNotification);
        $this->entityManager->flush();
    }
}
