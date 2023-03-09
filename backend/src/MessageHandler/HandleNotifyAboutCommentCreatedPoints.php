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
use App\Message\NotifyAboutCommentCreatedPoints;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutCommentCreatedPoints implements MessageHandlerInterface
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

    public function __invoke(NotifyAboutCommentCreatedPoints $commentCreatedPointsMessage)
    {
        $receiver = $this->userRepository->find($commentCreatedPointsMessage->getUserId());
        $campaign = $this->campaignRepository->find($commentCreatedPointsMessage->getCampaignId());
        $points = $commentCreatedPointsMessage->getPoints();

        if (!$receiver || !$campaign) {
            return;
        }

        $this->notifyAboutCommentCreatedPoints($receiver, $campaign, $points);
    }

    private function notifyAboutCommentCreatedPoints(User $receiver, Campaign $campaign, int $points): void
    {
        $pointsNotification = new Notification();

        $pointsNotification
            ->setReceiver($receiver)
            ->setText('1662032767')
            ->setCampaign($campaign)
            ->setSilent(NotificationType::NONE === $receiver->getNotificationSettings())
            ->setScorePoints($points);

        $this->entityManager->persist($pointsNotification);
        $this->entityManager->flush();
    }
}
