<?php

namespace App\NotificationHandler;

use App\Entity\Notification;
use App\Entity\Post;
use App\Message\NotifyCampaignMembersAboutNewPost;
use App\Repository\CampaignMemberRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class HandleNotifyCampaignPost implements MessageHandlerInterface
{
    private PostRepository $postRepository;

    private CampaignMemberRepository $campaignMemberRepository;

    private EntityManagerInterface $entityManager;

    public function __construct(
        PostRepository      $postRepository,
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->postRepository = $postRepository;
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(NotifyCampaignMembersAboutNewPost $post)
    {
        $post = $this->postRepository->find($post->getPostId());

        if (!$post) {
            return;
        }

        $this->handleNewCampaignPost($post);
    }

    private function handleNewCampaignPost(Post $post)
    {
        $campaignMembers = $this->campaignMemberRepository->findBy([
            'campaign' => $post->getCampaign()->getId()
        ]);

        foreach ($campaignMembers as $campaignMember) {
            $notification = new Notification();
            $notification
                ->setReceiver($campaignMember->getUser())
                ->setText("Neuer Post in der Kampange {$post->getCampaign()->getTitle()} von {$campaignMember->getUser()->getUserIdentifier()}");
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }
    }
}