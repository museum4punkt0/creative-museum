<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Notification;
use App\Message\EditorMessageReceived;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class CreateEditorNotificationController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function __invoke(Notification $data)
    {
        $message = new EditorMessageReceived(
            $data->getText(),
            $data->getHeadline(),
            $data->getCampaign()?->getId(),
            $data->getReceiver()?->getUuid()
        );
        $this->bus->dispatch($message);

        return new Response(null, Response::HTTP_CREATED);
    }
}
