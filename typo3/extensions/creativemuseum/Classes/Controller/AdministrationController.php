<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Controller;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;

class AdministrationController extends ActionController
{
    /**
     * @var string
     */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /**
     * @var UriBuilder
     */
    protected $beUriBuilder;

    public function __construct(UriBuilder $uriBuilder = null)
    {
        $this->beUriBuilder = $uriBuilder ?: GeneralUtility::makeInstance(UriBuilder::class);
    }

    protected function initializeView(ViewInterface $view)
    {
        /** @var BackendTemplateView $view */
        parent::initializeView($view);

        $this->generateMenu();
        $this->registerDocheaderButtons($view);
        $view->getModuleTemplate()->setFlashMessageQueue($this->controllerContext->getFlashMessageQueue());

        if ($view instanceof BackendTemplateView) {
            $view->getModuleTemplate()->getPageRenderer()->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
        }
    }

    public function indexAction()
    {

    }

    public function userOverviewAction()
    {
        
    }

    private function generateMenu()
    {

    }

    private function registerDocheaderButtons(BackendTemplateView $view)
    {
        $menuRegistry = $view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry();
        $menu = $menuRegistry->makeMenu();
        $menu->setIdentifier('creativeMuseumModuleMenu');

        $menuItem = $menu->makeMenuItem();
        $menuItem->setTitle('Kampagnenübersicht')
            ->setHref($this->beUriBuilder->buildUriFromRoute('system_CreativemuseumCmAdm.campaignOverview'));

        $menu->addMenuItem($menuItem);

        $menuItem2 = $menu->makeMenuItem();
        $menuItem2->setTitle('Benutzerübersicht')
            ->setHref($this->beUriBuilder->buildUriFromRoute('system_CreativemuseumCmAdm.userOverview'));

        $menu->addMenuItem($menuItem2);

        $menuRegistry->addMenu($menu);
    }
}