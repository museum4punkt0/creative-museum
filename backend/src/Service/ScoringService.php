<?php

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\CampaignMember;
use App\Entity\User;
use App\Repository\CampaignMemberRepository;
use Doctrine\ORM\EntityManagerInterface;

class ScoringService
{
    /**
     * @var int
     */
    private int $rewardPoints;

    /**
     * @var int
     */
    private int $registrationScorePoints;

    /**
     * @var int
     */
    private int $loginScorePoints;

    /**
     * @var int
     */
    private int $awardedScorePoints;

    /**
     * @var int
     */
    private int $awardReceivedScorePoints;

    /**
     * @var int
     */
    private int $postCreatedScorePoints;

    /**
     * @var int
     */
    private int $commentCreatedScorePoints;

    /**
     * @var int
     */
    private int $upvoteScorePoints;

    /**
     * @var CampaignMemberRepository
     */
    private CampaignMemberRepository $campaignMemberRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct
    (
        int $rewardPoints,
        int $registrationScorePoints,
        int $loginScorePoints,
        int $awardedScorePoints,
        int $awardReceivedScorePoints,
        int $postCreatedScorePoints,
        int $commentCreatedScorePoints,
        int $upvoteScorePoints,
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->rewardPoints = $rewardPoints;
        $this->registrationScorePoints = $registrationScorePoints;
        $this->loginScorePoints = $loginScorePoints;
        $this->awardedScorePoints = $awardedScorePoints;
        $this->awardReceivedScorePoints = $awardReceivedScorePoints;
        $this->postCreatedScorePoints = $postCreatedScorePoints;
        $this->commentCreatedScorePoints = $commentCreatedScorePoints;
        $this->upvoteScorePoints = $upvoteScorePoints;
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->entityManager = $entityManager;
    }

    public function handleRegistrationPoints(User $receiver, Campaign $campaign): void
    {

    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleUpvotePoints(User $receiver, Campaign $campaign): void
    {
        /**
         * @var CampaignMember $campaignMember
         */
        $campaignMember = $this->campaignMemberRepository->findOneBy([
            'user' => $receiver->getId(),
            'campaign' => $campaign->getId()
        ]);

        $campaignMember->setScore($campaignMember->getScore() + $this->upvoteScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        /**
         * @todo Notification
         */
    }
}