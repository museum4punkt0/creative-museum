<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Awarded;
use App\Enum\PointsReceivedType;
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
    private EventDispatcherInterface $eventDispatcher;

    private CampaignMemberRepository $campaignMemberRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => 'array'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createAwardedPoints', EventPriorities::POST_WRITE],
        ];
    }

    public function createAwardedPoints(ViewEvent $event): void
    {
        $awarded = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$awarded instanceof Awarded || Request::METHOD_POST !== $method) {
            return;
        }

        $awardGiver = $this->campaignMemberRepository->findOneBy([
            'user' => $awarded->getGiver()->getId(),
            'campaign' => $awarded->getAward()->getCampaign()->getId(),
        ]);
        $newGiverScore = $awardGiver->getScore() - $awarded->getAward()->getPrice();
        $awardGiver->setScore($newGiverScore);
        $this->entityManager->persist($awardGiver);
        $this->entityManager->flush();

        $giverPointsEvent = new CampaignPointsReceivedEvent(
            $awarded->getAward()->getCampaign()->getId(),
            $awarded->getGiver()->getId(),
            PointsReceivedType::AWARDED->value
        );
        $this->eventDispatcher->dispatch($giverPointsEvent, CampaignPointsReceivedEvent::NAME);

        $winnerPointsEvent = new CampaignPointsReceivedEvent(
            $awarded->getAward()->getCampaign()->getId(),
            $awarded->getWinner()->getId(),
            PointsReceivedType::AWARD_RECEIVED->value
        );
        $this->eventDispatcher->dispatch($winnerPointsEvent, CampaignPointsReceivedEvent::NAME);
    }
}
