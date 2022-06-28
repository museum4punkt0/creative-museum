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


    public function __construct(
        CampaignRepository $campaignRepository,
        UserRepository $userRepository,
    )
    {
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
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
    }
}