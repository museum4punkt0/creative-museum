<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\MediaObject;
use App\Enum\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request): MediaObject
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $mediaObject = new MediaObject();
        $mediaObject->file = $uploadedFile;

        if (null !== $request->get('description')) {
            $mediaObject->setDescription($request->get('description'));
        }

        if (null !== $request->get('type')) {
            $type = FileType::tryFrom($request->get('type')) ?? null;
            if (!is_null($type)) {
                $mediaObject->setType($type);
            }
        }

        return $mediaObject;
    }
}
