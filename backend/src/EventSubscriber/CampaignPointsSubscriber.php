<?php

namespace App\EventSubscriber;

use App\Event\CampaignPointsReceivedEvent;
use App\Repository\CampaignMemberRepository;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use App\Service\BadgeService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

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

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                ['onKernelResponsePre', 10],
                ['onKernelResponsePost', -10],
            ],
            CampaignPointsReceivedEvent::NAME => 'onCampaignPointsReceived',
        ];
    }

    public function onKernelResponsePre(ResponseEvent $event)
    {
    }

    public function onKernelResponsePost(ResponseEvent $event)
    {
    }

    public function onCampaignPointsReceived(CampaignPointsReceivedEvent $event)
    {
        $campaign = $this->campaignRepository->find($event->getCampaignId());
        $receiver = $this->userRepository->find($event->getReceiverId());

        $this->badgeService->getNextHigherBadge($campaign,$receiver);
    }
}