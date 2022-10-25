<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Message\EditorMessageReceived;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateEditorNotificationController extends AbstractController
{
    /**
     * @var MessageBusInterface
     */
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(Notification $data)
    {
        $message = new EditorMessageReceived(
            $data->getText(),
            $data->getCampaign()?->getId(),
            $data->getReceiver()?->getUuid()
        );
        $this->bus->dispatch($message);

        return new Response(null, Response::HTTP_CREATED);
    }
}