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
    private const REWARD_POINTS = 1;

    private const GIVER_REWARD_SCORE_POINTS = 10000;

    private const WINNER_POPULARITY_SCORE_POINTS = 20000;

    private CampaignMemberRepository $campaignMemberRepository;

    private EntityManagerInterface $entityManager;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface   $entityManager,
        EventDispatcherInterface $eventDispatcher
    )
    {
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
        $newGiverScore = $awardGiver->getScore() - $awarded->getAward()->getPrice() + self::GIVER_REWARD_SCORE_POINTS;

        $awardGiver
            ->setScore($newGiverScore)
            ->setRewardPoints($awardGiver->getRewardPoints() + self::REWARD_POINTS);

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
            ->setScore($awardWinner->getScore() + self::WINNER_POPULARITY_SCORE_POINTS);

        $this->entityManager->persist($awardWinner);
        $this->entityManager->flush();

        $winnerPointsEvent = new CampaignPointsReceivedEvent(
            $awarded->getAward()->getCampaign()->getId(),
            $awardWinner->getUser()->getId()
        );
        $this->eventDispatcher->dispatch($winnerPointsEvent, CampaignPointsReceivedEvent::NAME);
    }
}