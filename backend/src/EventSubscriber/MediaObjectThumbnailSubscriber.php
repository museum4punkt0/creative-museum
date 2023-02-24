<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\MediaObject;
use App\Enum\FileType;
use App\Repository\MediaObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class MediaObjectThumbnailSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly FFMpeg $ffmpeg,
        private readonly EntityManagerInterface $entityManager,
    ){}

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

        if (!$videoMediaObject instanceof MediaObject || Request::METHOD_POST !== $method || $videoMediaObject->getType() !== FileType::VIDEO) {
            return;
        }
        
        $video = $this->ffmpeg->open($videoMediaObject->getFile()->getPathname());
        $duration = $this->ffmpeg->getFFProbe()->format($video->getPathfile())->get('duration');
        $halfDuration = (int)round($duration / 2, 0, PHP_ROUND_HALF_DOWN);
        $fileName = 'thumbnail' . time() . '.png';
        $path = $videoMediaObject->getFile()->getPath() . '/' . $fileName;
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