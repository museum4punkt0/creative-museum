<?php

namespace App\MessageHandler;

use App\Entity\Badged;
use App\Entity\Notification;
use App\Enum\NotificationType;
use App\Message\NotifyNewBadgeReceived;
use App\Repository\BadgedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyNewBadgeReceived implements MessageHandlerInterface
{
    /**
     * @var BadgedRepository 
     */
    private BadgedRepository $badgedRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    
    public function __construct(BadgedRepository $badgedRepository, EntityManagerInterface $entityManager)
    {
        $this->badgedRepository = $badgedRepository;
        $this->entityManager = $entityManager;
    }
    
    public function __invoke(NotifyNewBadgeReceived $notifyNewBadgeReceived)
    {
        $badged = $this->badgedRepository->findOneBy(
            [
                'user' => $notifyNewBadgeReceived->getUserId(),
                'badge' => $notifyNewBadgeReceived->getBadgeId()
            ]
        );
        
        if (!$badged){
            return;
        }
        $this->handleNewBadgeReceivedNotification($badged);
    }

    private function handleNewBadgeReceivedNotification(Badged $badged)
    {
        $notification = new Notification();
        $notification
            ->setReceiver($badged->getUser())
            ->setText("Du hast das Badge {$badged->getBadge()->getTitle()} erhalten")
            ->setColor($badged->getBadge()->getCampaign()->getColor())
            ->setSilent($badged->getUser()->getNotificationSettings() === NotificationType::NONE);
        $this->entityManager->persist($notification);
        $this->entityManager->flush();
    }
}