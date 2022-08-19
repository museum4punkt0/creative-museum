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

        switch ($event->getPointsType()) {
            case PointsReceivedType::REGISTRATION->value:
                $this->scoringService->handleRegistrationPoints($receiver,$campaign);
                break;
            case PointsReceivedType::LOGIN->value:
                $this->scoringService->handleLoginPoints($receiver,$campaign);
                break;
            case PointsReceivedType::AWARD_RECEIVED->value:
                $this->scoringService->handleAwardReceivedPoints($receiver,$campaign);
                break;
            case PointsReceivedType::AWARDED->value:
                $this->scoringService->handleAwardedPoints($receiver,$campaign);
                break;
            case PointsReceivedType::POST_CREATED->value:
                $this->scoringService->handlePostCreatedPoints($receiver,$campaign);
                break;
            case PointsReceivedType::COMMENT_CREATED->value:
                $this->scoringService->handleCommentCreatedPoints($receiver,$campaign);
                break;
            case PointsReceivedType::UPVOTE->value:
                $this->scoringService->handleUpvotePoints($receiver,$campaign);
                break;
        }
    }
}