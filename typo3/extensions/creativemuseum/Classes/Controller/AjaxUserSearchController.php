<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Controller;

use JWIED\Creativemuseum\Domain\Model\Dto\UserListFilterDto;
use JWIED\Creativemuseum\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\JsonView;

class AjaxUserSearchController extends ActionController
{
    protected $defaultViewObjectName = JsonView::class;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param UserService|null $userService
     */
    public function __construct(UserService $userService = null)
    {
        $this->userService = $userService ?? GeneralUtility::makeInstance(UserService::class);
    }

    public function searchAction(ServerRequest $request): ResponseInterface
    {
        $needle = $request->getQueryParams()['query'];

        if (strlen($needle) < 3) {
            return new Response(null, \Symfony\Component\HttpFoundation\Response::HTTP_NOT_ACCEPTABLE);
        }

        $filter = new UserListFilterDto();
        $filter->setPage(1);
        $filter->setSearchString($needle);

        $users = $this->userService->getUsers($filter);

        if ($users['hydra:totalItems'] === 0) {
            return new Response(null, \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        }

        foreach ($users['hydra:member'] as &$member) {
            $member['value'] = "{$member['fullName']} (@{$member['username']})";
        }

        return new JsonResponse(
            [
                'suggestions' => $users['hydra:member']
            ]
        );
    }
}