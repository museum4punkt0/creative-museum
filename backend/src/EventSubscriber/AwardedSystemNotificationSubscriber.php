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
use App\Entity\Post;
use App\Enum\PostType;
use App\Repository\PostRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AwardedSystemNotificationSubscriber implements EventSubscriberInterface
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleAwardSystemNotification', EventPriorities::POST_WRITE],
        ];
    }

    public function handleAwardSystemNotification(ViewEvent $event): void
    {
        $awarded = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$awarded instanceof Awarded || Request::METHOD_POST !== $method) {
            return;
        }

        $metaData['giver']['username'] = $awarded->getGiver()->getUsername();
        $metaData['winner']['username'] = $awarded->getWinner()->getUsername();
        $metaData['award']['title'] = $awarded->getAward()->getTitle();
        $metaData = json_encode($metaData);

        $awardedSystemNotification = new Post();
        $awardedSystemNotification->setPostType(PostType::SYSTEM);
        $awardedSystemNotification->setCampaign($awarded->getAward()->getCampaign());
        $awardedSystemNotification->setBody('1663764951');
        $awardedSystemNotification->setPostMetadata($metaData);
        $this->postRepository->add($awardedSystemNotification);
    }
}
