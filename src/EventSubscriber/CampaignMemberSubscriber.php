<?php

namespace App\EventSubscriber;


use App\Entity\CampaignMember;
use App\Entity\Post;
use App\Repository\CampaignMemberRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;



class CampaignMemberSubscriber implements EventSubscriberInterface
{

    private $campaignMemberRepository;


    public function __construct(CampaignMemberRepository $campaignMemberRepository)
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleCampaigns', EventPriorities::POST_WRITE],
        ];
    }

    public function handleCampaigns(ViewEvent $event)
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method || $this->isCampiagnMember($post)) {
            return;
        }

        $campaignMember = new CampaignMember();
        $campaignMember->setCampaign($post->getCampaign());
        $campaignMember->setUser($post->getAuthor());
        $campaignMember->setScore(0);

        $this->campaignMemberRepository->add($campaignMember);

    }

    private function isCampiagnMember(Post $post): bool
    {
        $result = $this->campaignMemberRepository->findBy([
            'user' => $post->getAuthor()->getId(),
            'campaign' => $post->getCampaign()->getId()
        ]);

        return !empty($result);
    }
}