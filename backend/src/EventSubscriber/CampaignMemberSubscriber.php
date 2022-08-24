<?php

namespace App\EventSubscriber;

use App\Entity\Awarded;
use App\Entity\PollOptionChoice;
use App\Entity\Post;
use App\Entity\PostFeedback;
use App\Entity\Vote;
use App\Service\CampaignMemberService;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;

class CampaignMemberSubscriber implements EventSubscriberInterface
{
    private CampaignMemberService $campaignMemberService;

    public function __construct(CampaignMemberService $campaignMemberService)
    {
        $this->campaignMemberService = $campaignMemberService;
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['addCampaignMember', EventPriorities::POST_WRITE],
        ];
    }

    /**
     * @param ViewEvent $event
     * @return void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addCampaignMember(ViewEvent $event): void
    {
        $userInteractionObject = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($userInteractionObject instanceof Post) {
            $user = $userInteractionObject->getAuthor();
            $campaign = $userInteractionObject->getCampaign();
        } elseif (is_array($userInteractionObject) && array_key_exists('vote', $userInteractionObject) && $userInteractionObject['vote'] instanceof Vote) {
            $user = $userInteractionObject['vote']->getVoter();
            $campaign = $userInteractionObject['vote']->getPost()->getCampaign();
        } elseif ($userInteractionObject instanceof PostFeedback) {
            $user = $userInteractionObject->getUser();
            $campaign = $userInteractionObject->getPost()->getCampaign();
        } elseif ($userInteractionObject instanceof Awarded) {
            $user = $userInteractionObject->getGiver();
            $campaign = $userInteractionObject->getAward()->getCampaign();
        } elseif ($userInteractionObject instanceof PollOptionChoice) {
            $user = $userInteractionObject->getUser();
            $campaign = $userInteractionObject->getPollOption()->getPost()->getCampaign();
        } else {
            return;
        }

        if (Request::METHOD_POST !== $method || $this->campaignMemberService->isCampaignMember($campaign, $user)) {
            return;
        }

        $this->campaignMemberService->createCampaignMember($campaign, $user);
    }
}