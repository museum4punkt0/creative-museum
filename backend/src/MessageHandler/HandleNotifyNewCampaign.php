<?php

namespace App\MessageHandler;

use App\Entity\Campaign;
use App\Entity\Notification;
use App\Enum\NotificationType;
use App\Message\NotifyUsersAboutNewCampaign;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyNewCampaign implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    private CampaignRepository $campaignRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository      $userRepository,
        CampaignRepository $campaignRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->campaignRepository = $campaignRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param NotifyUsersAboutNewCampaign $campaign
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

    /**
     * @param Campaign $campaign
     * @return void
     */
    private function handleNewCampaignNotification(Campaign $campaign): void
    {
        $users = $this->userRepository->getActiveUsers();

        foreach ($users as $user) {
            $notification = new Notification();
            $notification
                ->setReceiver($user)
                ->setText("Neue Kampange {$campaign->getTitle()} verfügbar")
                ->setColor($campaign->getColor())
                ->setSilent($user->getNotificationSettings() === NotificationType::NONE);
            $this->entityManager->persist($notification);
        }
        $campaign->setNotified(true);
        $this->entityManager->persist($campaign);
        $this->entityManager->flush();
    }
}