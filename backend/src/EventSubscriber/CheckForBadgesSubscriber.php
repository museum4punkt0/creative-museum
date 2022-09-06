<?php

namespace App\EventSubscriber;

use App\Entity\Badge;
use App\Enum\BadgeType;
use App\Event\CheckForBadgesEvent;
use App\Message\NotifyNewBadgeReceived;
use App\Repository\CampaignMemberRepository;
use App\Service\BadgeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckForBadgesSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CampaignMemberRepository $campaignMemberRepository,
        private readonly BadgeService $badgeService,
        private readonly MessageBusInterface $bus,
        private readonly LockFactory $lockFactory
    ) {}

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CheckForBadgesEvent::NAME => 'checkForBadges',
        ];
    }

    public function checkForBadges(CheckForBadgesEvent $event)
    {
        $lock = $this->lockFactory->createLock(
            'check-badges-' . $event->getCampaignId() . '-' . $event->getUserId()
        );

        $i = 0;
        while ($lock->isAcquired() && $i <= 5){
            usleep(100000);
            $i++;
        }
        if ($i > 10){
            return;
        }

        $lock->acquire();

        $campaignMember = $this->campaignMemberRepository->findOneBy([
            'user' => $event->getUserId(),
            'campaign' => $event->getCampaignId()
        ]);

        $unbadged = $this->badgeService->getUnbadged($campaignMember->getCampaign(), $campaignMember->getUser());

        /**
         * @var Badge $badge
         */
        foreach ($unbadged as $badge) {
            if (
                (BadgeType::SCORING === $badge->getType() && $campaignMember->getScore() >= $badge->getThreshold()) ||
                (BadgeType::REWARD_POINTS === $badge->getType() && $campaignMember->getRewardPoints() >= $badge->getThreshold())
            ) {
                $this->badgeService->createBadged($badge, $campaignMember->getUser());
                $this->bus->dispatch(new NotifyNewBadgeReceived($campaignMember->getUser()->getId(), $badge->getId()));
            }
        }

        $lock->release();
    }
}