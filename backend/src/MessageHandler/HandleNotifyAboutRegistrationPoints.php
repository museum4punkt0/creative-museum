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
use App\Message\NotifyAboutRegistrationPoints;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyAboutRegistrationPoints implements MessageHandlerInterface
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

    public function __invoke(NotifyAboutRegistrationPoints $registrationPointsMessage)
    {
        $receiver = $this->userRepository->find($registrationPointsMessage->getUserId());
        $campaign = $this->campaignRepository->find($registrationPointsMessage->getCampaignId());
        $points = $registrationPointsMessage->getPoints();

        if (!$receiver || !$campaign) {
            return;
        }

        $this->notifyAboutRegistrationPoints($receiver, $campaign, $points);
    }

    private function notifyAboutRegistrationPoints(User $receiver, Campaign $campaign, int $points): void
    {
        $pointsNotification = new Notification();

        $pointsNotification
            ->setReceiver($receiver)
            ->setText("Du hast {$points} Punkte für deine Registrierung in der Kampange {$campaign->getTitle()} erhalten!")
            ->setCampaign($campaign)
            ->setSilent(NotificationType::NONE === $receiver->getNotificationSettings())
            ->setColor($campaign->getColor())
            ->setScorePoints($points);

        $this->entityManager->persist($pointsNotification);
        $this->entityManager->flush();
    }
}
