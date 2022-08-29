<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Enum\PointsReceivedType;
use App\Event\CampaignPointsReceivedEvent;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use App\Service\CampaignMemberService;
use App\Service\ScoringService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CampaignPointsReceivedSubscriber implements EventSubscriberInterface
{
    private ScoringService $scoringService;

    private CampaignRepository $campaignRepository;

    private UserRepository $userRepository;

    private CampaignMemberService $campaignMemberService;

    public function __construct(
        ScoringService $scoringService,
        CampaignRepository $campaignRepository,
        UserRepository $userRepository,
        CampaignMemberService $campaignMemberService
    ) {
        $this->scoringService = $scoringService;
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
        $this->campaignMemberService = $campaignMemberService;
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

    public function onCampaignPointsReceived(CampaignPointsReceivedEvent $event): void
    {
        $campaign = $this->campaignRepository->find($event->getCampaignId());
        $receiver = $this->userRepository->find($event->getReceiverId());

        if (!$this->campaignMemberService->isCampaignMember($campaign, $receiver)) {
            $this->campaignMemberService->createCampaignMember($campaign, $receiver);
        }

        switch ($event->getPointsType()) {
            case PointsReceivedType::REGISTRATION->value:
                $this->scoringService->handleRegistrationPoints($receiver, $campaign);
                break;
            case PointsReceivedType::LOGIN->value:
                $this->scoringService->handleLoginPoints($receiver, $campaign);
                break;
            case PointsReceivedType::AWARD_RECEIVED->value:
                $this->scoringService->handleAwardReceivedPoints($receiver, $campaign);
                break;
            case PointsReceivedType::AWARDED->value:
                $this->scoringService->handleAwardedPoints($receiver, $campaign);
                break;
            case PointsReceivedType::POST_CREATED->value:
                $this->scoringService->handlePostCreatedPoints($receiver, $campaign);
                break;
            case PointsReceivedType::COMMENT_CREATED->value:
                $this->scoringService->handleCommentCreatedPoints($receiver, $campaign);
                break;
            case PointsReceivedType::UPVOTE->value:
                $this->scoringService->handleUpvotePoints($receiver, $campaign);
                break;
            case PointsReceivedType::FEEDBACK->value:
                $this->scoringService->handleFeedbackPoints($receiver, $campaign);
                break;
        }
    }
}
