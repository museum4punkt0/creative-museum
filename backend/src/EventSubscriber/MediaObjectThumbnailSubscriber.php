<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\MediaObject;
use App\Enum\FileType;
use Doctrine\ORM\EntityManagerInterface;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MediaObjectThumbnailSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly FFMpeg $ffmpeg,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array[]
     */
    #[ArrayShape([KernelEvents::VIEW => 'array'])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['createVideoThumbnail', EventPriorities::POST_WRITE],
        ];
    }

    public function createVideoThumbnail(ViewEvent $event)
    {
        $videoMediaObject = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$videoMediaObject instanceof MediaObject || Request::METHOD_POST !== $method || FileType::VIDEO !== $videoMediaObject->getType()) {
            return;
        }

        $video = $this->ffmpeg->open($videoMediaObject->getFile()->getPathname());
        $duration = $this->ffmpeg->getFFProbe()->format($video->getPathfile())->get('duration');
        $halfDuration = (int) round($duration / 2, 0, PHP_ROUND_HALF_DOWN);
        $fileName = 'thumbnail'.time().$videoMediaObject->getId().'.png';
        $path = $videoMediaObject->getFile()->getPath().'/'.$fileName;
        $video->frame(TimeCode::fromSeconds($halfDuration))->save($path);

        $videoThumbnail = new MediaObject();
        $videoThumbnail->setType(FileType::IMAGE);
        $videoThumbnail->setFilepath($fileName);
        $videoThumbnail->setDescription('thumbnail');
        $videoThumbnail->setFile(new File($path));
        $videoMediaObject->setThumbnail($videoThumbnail);
        $this->entityManager->persist($videoMediaObject);
        $this->entityManager->flush();
    }
}
