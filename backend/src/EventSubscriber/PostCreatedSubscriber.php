<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Post;
use App\Entity\User;
use App\Enum\MailType;
use App\Enum\PointsReceivedType;
use App\Event\CampaignPointsReceivedEvent;
use App\Repository\UserRepository;
use App\Service\MailService;
use JetBrains\PhpStorm\ArrayShape;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PostCreatedSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly MailService $mailService,
        private readonly UserRepository $userRepository
    ) {
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => 'array'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['postCreated', EventPriorities::POST_WRITE],
        ];
    }

    public function postCreated(ViewEvent $event): void
    {
        $post = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$post instanceof Post || Request::METHOD_POST !== $method) {
            return;
        }

        if ($post->getParent()) {
            $postPointsEvent = new CampaignPointsReceivedEvent(
                $post->getCampaign()->getId(),
                $post->getAuthor()->getId(),
                PointsReceivedType::COMMENT_CREATED->value
            );
            if ($post->getAuthor() !== $post->getParent()->getAuthor()) {
                $this->mailService->sendMail(MailType::POST_COMMENTED->value, $post->getParent()->getAuthor(), ['comment' => $post]);
            }
            $this->checkForMention($post, MailType::COMMENT_MENTION);
        } else {
            $postPointsEvent = new CampaignPointsReceivedEvent(
                $post->getCampaign()->getId(),
                $post->getAuthor()->getId(),
                PointsReceivedType::POST_CREATED->value
            );
            $this->checkForMention($post, MailType::POST_MENTION);
        }

        $this->eventDispatcher->dispatch($postPointsEvent, CampaignPointsReceivedEvent::NAME);
    }

    protected function checkForMention(Post $post, MailType $mailType): void
    {
        preg_match_all('/data-label="(.*?)"/', $post->getBody(), $mentions);
        $mentions = array_unique($mentions[1]);

        if (0 === count($mentions)) {
            return;
        }

        foreach ($mentions as $mention) {
            $mentionedUser = $this->userRepository->findOneBy([
                'username' => $mention,
                'active' => 1,
                'deleted' => 0,
            ]);

            if (!($mentionedUser instanceof User)) {
                continue;
            }

            $this->mailService->sendMail(
                $mailType->value,
                $mentionedUser,
                [
                    'post' => $post,
                ]
            );
        }
    }
}
