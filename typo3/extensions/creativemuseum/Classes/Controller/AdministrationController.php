<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Controller;

use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class AdministrationController extends ActionController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * @var UriBuilder
     */
    protected $uriBuilder;

    /**
     * @var CampaignService
     */
    protected $campaignService;

    public function __construct(
        UriBuilder $uriBuilder = null,
        CampaignService $campaignService = null
    ) {
        $this->uriBuilder = $uriBuilder ?: GeneralUtility::makeInstance(UriBuilder::class);
        $this->campaignService = $campaignService ?: GeneralUtility::makeInstance(CampaignService::class);
    }

    protected function initializeView(ViewInterface $view)
    {
        /** @var BackendTemplateView $view */
        parent::initializeView($view);

        $this->generateMenu($view);
        $this->registerDocheaderButtons($view);
        $view->getModuleTemplate()->setFlashMessageQueue($this->controllerContext->getFlashMessageQueue());

        if ($view instanceof BackendTemplateView) {
            $view->getModuleTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
        }
    }

    public function indexAction()
    {
        $campaigns = $this->campaignService->getCampaigns();
        $this->view->assign('campaigns', $campaigns);
    }

    public function userOverviewAction()
    {
        
    }

    private function generateMenu(BackendTemplateView $view)
    {
        $menuRegistry = $view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry();
        $menu = $menuRegistry->makeMenu();
        $menu->setIdentifier('creativeMuseumModuleMenu');

        $menuItems = ['Administration' => ['index', 'userOverview']];

        foreach ($menuItems as $controller => $actions) {
            $underscoredControllerName = GeneralUtility::camelCaseToLowerCaseUnderscored($controller);
            foreach ($actions as $action) {
                $menuItem = $menu->makeMenuItem();
                $menuItem->setTitle(
                    LocalizationUtility::translate(
                        'LLL:EXT:creativemuseum/Resources/Private/Language/locallang.xlf:menu.' .
                        $underscoredControllerName . '.' . GeneralUtility::camelCaseToLowerCaseUnderscored($action),
                        ''
                    )
                );
                if ($this->request->getControllerName() === $controller) {
                    $isActive = $this->request->getControllerActionName() === $action ? true : false;
                } else {
                    $isActive = false;
                }
                $menuItem->setActive($isActive);
                $menuItem->setHref($this->getHref($controller, $action));
                $menu->addMenuItem($menuItem);
            }
        }

        $menuRegistry->addMenu($menu);
    }

    private function registerDocheaderButtons(BackendTemplateView $view)
    {

    }

    protected function getHref($controller, $action)
    {
        $this->uriBuilder->setRequest($this->request);
        return $this->uriBuilder->reset()->uriFor($action, [], $controller);
    }
}