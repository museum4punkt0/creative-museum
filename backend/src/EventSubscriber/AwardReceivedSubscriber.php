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
use App\Entity\CampaignMember;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AwardReceivedSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['addCampaignMemberIfNecessary', EventPriorities::PRE_WRITE],
        ];
    }

    public function addCampaignMemberIfNecessary(ViewEvent $event): void
    {
        $subject = $event->getControllerResult();

        if (! $subject instanceof Awarded) {
            return;
        }

        $campaignMemberRepository = $this->entityManager->getRepository(CampaignMember::class);

        $member = $campaignMemberRepository->findOneBy([
            'user' => $subject->getWinner(),
            'campaign' => $subject->getAward()->getCampaign()
        ]);

        if ($member instanceof CampaignMember) {
            return;
        }

        $this->entityManager->persist(
            CampaignMember::create($subject->getAward()->getCampaign(), $subject->getWinner())
        );

        $this->entityManager->flush();
    }
}
