<?php

namespace App\EventSubscriber;

use App\Enum\PointsReceivedType;
use App\Event\CampaignPointsReceivedEvent;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use App\Service\ScoringService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CampaignPointsReceivedSubscriber implements EventSubscriberInterface
{
    /**
     * @var ScoringService
     */
    private ScoringService $scoringService;

    /**
     * @var CampaignRepository
     */
    private CampaignRepository $campaignRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct
    (
        ScoringService     $scoringService,
        CampaignRepository $campaignRepository,
        UserRepository     $userRepository,
    )
    {
        $this->scoringService = $scoringService;
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

    /**
     * @param CampaignPointsReceivedEvent $event
     * @return void
     */
    public function onCampaignPointsReceived(CampaignPointsReceivedEvent $event): void
    {
        $campaign = $this->campaignRepository->find($event->getCampaignId());
        $receiver = $this->userRepository->find($event->getReceiverId());
        $test = $event->getPointsType();

        switch ($event->getPointsType()) {
            case PointsReceivedType::REGISTRATION->value:

            case PointsReceivedType::LOGIN->value:

            case PointsReceivedType::AWARDED->value:

            case PointsReceivedType::AWARD_RECEIVED->value:

            case PointsReceivedType::POST_CREATED->value:

            case PointsReceivedType::COMMENT_CREATED->value:

            case PointsReceivedType::UPVOTE->value:
                $this->scoringService->handleUpvotePoints($receiver,$campaign);
        }
    }
}