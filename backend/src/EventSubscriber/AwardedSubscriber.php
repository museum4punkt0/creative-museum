<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Awarded;
use App\Event\CampaignPointsReceivedEvent;
use App\Repository\CampaignMemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\ArrayShape;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AwardedSubscriber implements EventSubscriberInterface
{

    /**
     * @var int
     */
    private int $rewardPoints;

    /**
     * @var int
     */
    private int $giverRewardScorePoints;

    /**
     * @var int
     */
    private int $winnerPopularityScorePoints;

    /**
     * @var CampaignMemberRepository
     */
    private CampaignMemberRepository $campaignMemberRepository;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        int $rewardPoints,
        int $giverRewardScorePoints,
        int $winnerPopularityScorePoints,
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface   $entityManager,
        EventDispatcherInterface $eventDispatcher,
    )
    {
        $this->rewardPoints = $rewardPoints;
        $this->giverRewardScorePoints = $giverRewardScorePoints;
        $this->winnerPopularityScorePoints = $winnerPopularityScorePoints;
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createAwardedPoints', EventPriorities::POST_WRITE],
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     */
    public function createAwardedPoints(ViewEvent $event): void
    {
        $awarded = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$awarded instanceof Awarded || Request::METHOD_POST !== $method) {
            return;
        }

        $awardGiver = $this->campaignMemberRepository->findOneBy([
            'user' => $awarded->getGiver()->getId(),
            'campaign' => $awarded->getAward()->getCampaign()->getId()
        ]);
        $newGiverScore = $awardGiver->getScore() - $awarded->getAward()->getPrice() + $this->giverRewardScorePoints;

        $awardGiver
            ->setScore($newGiverScore)
            ->setRewardPoints($awardGiver->getRewardPoints() + $this->rewardPoints);

        $this->entityManager->persist($awardGiver);

        $giverPointsEvent = new CampaignPointsReceivedEvent(
            $awarded->getAward()->getCampaign()->getId(),
            $awardGiver->getUser()->getId()
        );
        $this->eventDispatcher->dispatch($giverPointsEvent, CampaignPointsReceivedEvent::NAME);

        $awardWinner = $this->campaignMemberRepository->findOneBy([
            'user' => $awarded->getWinner()->getId(),
            'campaign' => $awarded->getAward()->getCampaign()->getId()
        ]);

        $awardWinner
            ->setScore($awardWinner->getScore() + $this->winnerPopularityScorePoints);

        $this->entityManager->persist($awardWinner);
        $this->entityManager->flush();

        $winnerPointsEvent = new CampaignPointsReceivedEvent(
            $awarded->getAward()->getCampaign()->getId(),
            $awardWinner->getUser()->getId()
        );
        $this->eventDispatcher->dispatch($winnerPointsEvent, CampaignPointsReceivedEvent::NAME);
    }
}