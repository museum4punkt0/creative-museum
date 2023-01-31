<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Enum\NotificationType;
use App\Message\EditorMessageReceived;
use App\Repository\CampaignMemberRepository;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class HandleEditorMessage implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    private CampaignRepository $campaignRepository;

    private CampaignMemberRepository $memberRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserRepository $userRepository,
        CampaignRepository $campaignRepository,
        CampaignMemberRepository $memberRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->campaignRepository = $campaignRepository;
        $this->memberRepository = $memberRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(EditorMessageReceived $editorMessageReceived)
    {


        if ($editorMessageReceived->getUserUuid() !== null) {
            $this->handleUserNotification($editorMessageReceived);
            return;
        }

        if ($editorMessageReceived->getCampaignId() !== null) {
            $this->handleCampaignNotification($editorMessageReceived);
            return;
        }

        $this->handleGlobalNotification($editorMessageReceived);
    }

    private function handleUserNotification(EditorMessageReceived $message): void
    {
        $user = $this->userRepository->findOneBy(['uuid' => $message->getUserUuid()]);

        if (null === $user) {
            return;
        }

        $notification = new Notification();
        $notification
            ->setReceiver($user)
            ->setText($message->getMessage())
            ->setSilent(NotificationType::NONE === $user->getNotificationSettings())
            ->setEditorNotification(true);

        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }

    private function handleCampaignNotification(EditorMessageReceived $message): void
    {
        $campaignMembers = $this->memberRepository->findBy(['campaign' => $message->getCampaignId()]);
        $campaign = $this->campaignRepository->findOneBy(['id' => $message->getCampaignId()]);

        if (null === $campaignMembers || null === $campaign) {
            return;
        }

        $i = 0;
        foreach($campaignMembers as $member) {
            ++$i;
            if (!$member->getUser()->getActive() || $member->getUser()->getDeleted()) {
                continue;
            }
            $notification = new Notification();
            $notification->setReceiver($member->getUser())
                ->setText($message->getMessage())
                ->setCampaign($campaign)
                ->setSilent(NotificationType::NONE === $member->getUser()->getNotificationSettings())
                ->setEditorNotification(true);
            $this->entityManager->persist($notification);

            if ($i % 100 === 0) {
                $this->entityManager->flush();
            }
        }
        $this->entityManager->flush();
    }

    private function handleGlobalNotification(EditorMessageReceived $message): void
    {
        $users = $this->userRepository->findBy(['deleted' => false, 'active' => true]);

        $i = 0;
        foreach ($users as $user) {
            ++$i;

            $notification = new Notification();
            $notification->setReceiver($user)
                ->setText($message->getMessage())
                ->setSilent(NotificationType::NONE === $user->getNotificationSettings())
                ->setEditorNotification(true);
            $this->entityManager->persist($notification);

            if ($i % 100 === 0) {
                $this->entityManager->flush();
            }
        }
        $this->entityManager->flush();
    }
}
