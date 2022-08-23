<?php

namespace App\Service;

use App\Entity\Campaign;
use App\Entity\CampaignMember;
use App\Entity\User;
use App\Message\NotifyAboutAwardedPoints;
use App\Message\NotifyAboutAwardReceivedPoints;
use App\Message\NotifyAboutCommentCreatedPoints;
use App\Message\NotifyAboutLoginPoints;
use App\Message\NotifyAboutPostCreatedPoints;
use App\Message\NotifyAboutRegistrationPoints;
use App\Message\NotifyAboutUpvotePoints;
use App\Repository\CampaignMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

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

    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    public function __construct
    (
        int                      $rewardPoints,
        int                      $registrationScorePoints,
        int                      $loginScorePoints,
        int                      $awardedScorePoints,
        int                      $awardReceivedScorePoints,
        int                      $postCreatedScorePoints,
        int                      $commentCreatedScorePoints,
        int                      $upvoteScorePoints,
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface   $entityManager,
        MessageBusInterface      $bus,
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
        $this->bus = $bus;
    }

    /**
     * @param int $receiverId
     * @param int $campaignId
     * @return CampaignMember
     */
    private function getCampaignMember(int $receiverId, int $campaignId): CampaignMember
    {
        /**
         * @var CampaignMember $campaignMember
         */
        $campaignMember = $this->campaignMemberRepository->findOneBy([
            'user' => $receiverId,
            'campaign' => $campaignId
        ]);

        return $campaignMember;
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleRegistrationPoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->registrationScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutRegistrationPoints($receiver->getId(), $this->registrationScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleLoginPoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->loginScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutLoginPoints($receiver->getId(), $this->loginScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleAwardReceivedPoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->awardReceivedScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutAwardReceivedPoints($receiver->getId(), $this->awardReceivedScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleAwardedPoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->awardedScorePoints);
        $campaignMember->setRewardPoints($campaignMember->getRewardPoints() + $this->rewardPoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutAwardedPoints($receiver->getId(), $this->awardedScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handlePostCreatedPoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->postCreatedScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutPostCreatedPoints($receiver->getId(), $this->postCreatedScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleCommentCreatedPoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->commentCreatedScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutCommentCreatedPoints($receiver->getId(), $this->commentCreatedScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }

    /**
     * @param User $receiver
     * @param Campaign $campaign
     * @return void
     */
    public function handleUpvotePoints(User $receiver, Campaign $campaign): void
    {
        $campaignMember = $this->getCampaignMember($receiver->getId(), $campaign->getId());

        $campaignMember->setScore($campaignMember->getScore() + $this->upvoteScorePoints);
        $this->entityManager->persist($campaignMember);
        $this->entityManager->flush();

        $pointsNotification = new NotifyAboutUpvotePoints($receiver->getId(), $this->upvoteScorePoints, $campaign->getId());
        $this->bus->dispatch($pointsNotification);
    }
}