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
use App\Enum\MailType;
use App\Enum\NotificationType;
use App\Message\NotifyUsersAboutNewCampaign;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyNewCampaign implements MessageHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly CampaignRepository $campaignRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly MailService $mailService
    ){}

    /**
     * @return void
     */
    public function __invoke(NotifyUsersAboutNewCampaign $campaign)
    {
        $campaign = $this->campaignRepository->find($campaign->getCampaignId());

        if (!$campaign) {
            return;
        }

        $this->handleNewCampaignNotification($campaign);
    }

    private function handleNewCampaignNotification(Campaign $campaign): void
    {
        $users = $this->userRepository->getActiveUsers();

        foreach ($users as $user) {
            $notification = new Notification();
            $notification
                ->setReceiver($user)
                ->setText("1662033370")
                ->setCampaign($campaign)
                ->setSilent(NotificationType::NONE === $user->getNotificationSettings());
            $this->entityManager->persist($notification);

            $this->mailService->sendMail(MailType::NEW_CAMPAIGN->value,$user, ['campaign' => $campaign]);
        }
        $campaign->setNotified(true);
        $this->entityManager->persist($campaign);
        $this->entityManager->flush();
    }
}
