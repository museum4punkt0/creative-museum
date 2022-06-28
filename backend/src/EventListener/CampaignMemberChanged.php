<?php

namespace App\EventListener;

use App\Entity\Badge;
use App\Entity\CampaignMember;
use App\Enum\BadgeType;
use App\Message\NotifyNewBadgeReceived;
use App\Service\BadgeService;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Messenger\MessageBusInterface;

class CampaignMemberChanged
{
    /**
     * @var BadgeService
     */
    private BadgeService $badgeService;

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    public function __construct(BadgeService $badgeService, MessageBusInterface $bus)
    {
        $this->badgeService = $badgeService;
        $this->bus = $bus;
    }

    public function preUpdate(CampaignMember $campaignMember, PreUpdateEventArgs $event): void
    {
        if (!array_key_exists('score',$event->getEntityChangeSet()) && !array_key_exists('rewardPoints',$event->getEntityChangeSet())){
            return;
        }

        $unbadged = $this->badgeService->getUnbadged($campaignMember->getCampaign(), $campaignMember->getUser());

        /**
         * @var Badge $badge
         */
        foreach ($unbadged as $badge) {
            if (($badge->getType() === BadgeType::SCORING && $campaignMember->getScore() >= $badge->getThreshold()) || ($badge->getType() === BadgeType::REWARD_POINTS && $campaignMember->getRewardPoints() >= $badge->getThreshold())) {
                $this->badgeService->createBadged($badge,$campaignMember->getUser());
                $this->bus->dispatch(new NotifyNewBadgeReceived($campaignMember->getUser()->getId(),$badge->getId()));
            }
        }
    }
}