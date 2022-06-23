<?php

namespace App\EventSubscriber;

use App\Event\CampaignPointsReceivedEvent;
use App\Repository\CampaignMemberRepository;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use App\Service\BadgeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CampaignPointsSubscriber implements EventSubscriberInterface
{
    /**
     * @var CampaignRepository
     */
    private CampaignRepository $campaignRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * @var BadgeService
     */
    private BadgeService $badgeService;

    /**
     * @var CampaignMemberRepository
     */
    private CampaignMemberRepository $campaignMemberRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        UserRepository $userRepository,
        BadgeService $badgeService,
        CampaignMemberRepository $campaignMemberRepository
    )
    {
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
        $this->badgeService = $badgeService;
        $this->campaignMemberRepository = $campaignMemberRepository;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            CampaignPointsReceivedEvent::NAME => 'onCampaignPointsReceived',
        ];
    }

    public function onCampaignPointsReceived(CampaignPointsReceivedEvent $event)
    {
        $campaign = $this->campaignRepository->find($event->getCampaignId());
        $receiver = $this->userRepository->find($event->getReceiverId());

        $this->badgeService->getNextHigherBadge($campaign,$receiver);
    }
}