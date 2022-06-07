<?php

namespace App\EventSubscriber;

use App\Entity\CampaignMember;
use App\Entity\Post;
use App\Repository\CampaignMemberRepository;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;

class CampaignMemberSubscriber implements EventSubscriberInterface
{
    private CampaignMemberRepository $campaignMemberRepository;

    public function __construct(CampaignMemberRepository $campaignMemberRepository)
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
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
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method || $this->isCampaignMember($post)) {
            return;
        }

        $campaignMember = new CampaignMember();
        $campaignMember
            ->setCampaign($post->getCampaign())
            ->setUser($post->getAuthor());

        $this->campaignMemberRepository->add($campaignMember);
    }

    /**
     * @param Post $post
     * @return bool
     */
    private function isCampaignMember(Post $post): bool
    {
        $result = $this->campaignMemberRepository->findBy([
            'user' => $post->getAuthor()->getId(),
            'campaign' => $post->getCampaign()->getId()
        ]);

        return !empty($result);
    }
}