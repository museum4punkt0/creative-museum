<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use TYPO3\CMS\Core\Http\UploadedFile;

class FileUploadService extends CmApiService
{
    const ENDPOINT = 'v1/media_objects';

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function sendFile(UploadedFile $file): string
    {
        return $this->post(null, $file);
    }
}