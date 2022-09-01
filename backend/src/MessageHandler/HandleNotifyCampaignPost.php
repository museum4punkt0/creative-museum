<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\MessageHandler;

use App\Entity\Notification;
use App\Entity\Post;
use App\Entity\User;
use App\Enum\NotificationType;
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
        PostRepository $postRepository,
        CampaignMemberRepository $campaignMemberRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->postRepository = $postRepository;
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function __invoke(NotifyCampaignMembersAboutNewPost $post)
    {
        $post = $this->postRepository->find($post->getPostId());

        if (!$post) {
            return;
        }

        $this->handleNewCampaignPost($post);
    }

    private function handleNewCampaignPost(Post $post): void
    {
        $campaignMembers = $this->campaignMemberRepository->findBy([
            'campaign' => $post->getCampaign()->getId(),
        ]);
        $campaignColor = $post->getCampaign()->getColor();

        foreach ($campaignMembers as $campaignMember) {
            $notification = new Notification();
            $notification
                ->setReceiver($campaignMember->getUser())
                ->setText("1662033266")
                ->setPost($post)
                ->setColor($campaignColor)
                ->setSilent(NotificationType::NONE === $campaignMember->getUser()->getNotificationSettings());
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }
    }

    private function isSilentNotification(User $user): bool
    {
        return NotificationType::NONE === $user->getNotificationSettings();
    }
}
