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
use Symfony\Component\Lock\Store\SemaphoreStore;
use Symfony\Component\Messenger\MessageBusInterface;

class CheckForBadgesSubscriber implements EventSubscriberInterface
{

    private CampaignMemberRepository $campaignMemberRepository;

    private BadgeService $badgeService;

    private MessageBusInterface $bus;

    public function __construct(
        CampaignMemberRepository $campaignMemberRepository,
        BadgeService $badgeService,
        MessageBusInterface $bus
    )
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->badgeService = $badgeService;
        $this->bus = $bus;
    }

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
        $store = new SemaphoreStore();
        $factory = new LockFactory($store);

        $lock = $factory->createLock('check-badges');

        $i = 0;
        while (!$lock->acquire() && $i <= 10){
            usleep(300000);
            $i++;
        }
        if ($i > 10){
            return;
        }

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