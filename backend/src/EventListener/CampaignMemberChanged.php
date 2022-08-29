<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
    private BadgeService $badgeService;

    private MessageBusInterface $bus;

    public function __construct(BadgeService $badgeService, MessageBusInterface $bus)
    {
        $this->badgeService = $badgeService;
        $this->bus = $bus;
    }

    public function preUpdate(CampaignMember $campaignMember, PreUpdateEventArgs $event): void
    {
        if (!array_key_exists('score', $event->getEntityChangeSet()) && !array_key_exists('rewardPoints', $event->getEntityChangeSet())) {
            return;
        }

        $unbadged = $this->badgeService->getUnbadged($campaignMember->getCampaign(), $campaignMember->getUser());

        /**
         * @var Badge $badge
         */
        foreach ($unbadged as $badge) {
            if ((BadgeType::SCORING === $badge->getType() && $campaignMember->getScore() >= $badge->getThreshold()) || (BadgeType::REWARD_POINTS === $badge->getType() && $campaignMember->getRewardPoints() >= $badge->getThreshold())) {
                $this->badgeService->createBadged($badge, $campaignMember->getUser());
                $this->bus->dispatch(new NotifyNewBadgeReceived($campaignMember->getUser()->getId(), $badge->getId()));
            }
        }
    }
}
