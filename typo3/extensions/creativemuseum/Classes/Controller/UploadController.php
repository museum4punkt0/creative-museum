<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Controller;

use JWIED\Creativemuseum\Service\FileUploadService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;

class UploadController extends ActionController
{
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var FileUploadService
     */
    protected $fileUploadService;

    /**
     * @param FileUploadService|null $fileUploadService
     */
    public function __construct(FileUploadService $fileUploadService = null)
    {
        $this->fileUploadService = $fileUploadService ?? GeneralUtility::makeInstance(FileUploadService::class);
    }

    public function uploadAction(ServerRequest $request): ResponseInterface
    {
        $files = $request->getUploadedFiles();

        $identifier = $this->fileUploadService->sendFile(reset($files));

        return new JsonResponse(['file' => $identifier], 200);
    }
}