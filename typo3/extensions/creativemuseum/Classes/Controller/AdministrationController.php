<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Controller;

use JWIED\Creativemuseum\Domain\Model\Dto\CampaignDto;
use JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto;
use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
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

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    public function __construct(
        UriBuilder $uriBuilder,
        CampaignService $campaignService,
        IconFactory $iconFactory
    ) {
        $this->uriBuilder = $uriBuilder;
        $this->campaignService = $campaignService;
        $this->iconFactory = $iconFactory;
    }

    /**
     * @param ViewInterface $view
     * @return void
     */
    protected function initializeView(ViewInterface $view)
    {
        /** @var BackendTemplateView $view */
        parent::initializeView($view);

        $this->generateMenu($view);
        $this->registerDocheaderButtons($view);
        $view->getModuleTemplate()->setFlashMessageQueue($this->controllerContext->getFlashMessageQueue());

        /** @var BackendTemplateView $view */
        if ($view instanceof BackendTemplateView) {
            /** @var PageRenderer $pageRenderer */
            $pageRenderer = $this->view->getModuleTemplate()->getPageRenderer();
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/Modal');
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/DocumentSaveActions');
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/DateTimePicker');
            $pageRenderer->loadRequireJsModule('TYPO3/CMS/Creativemuseum/RemoveCampaignModal');

            if (in_array($this->actionMethodName, ['newCampaignAction', 'editCampaignAction'])) {
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Creativemuseum/CampaignHandling');
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/ColorPicker');

                $pageRenderer->addJsFile(
                    'EXT:creativemuseum/Resources/Public/JavaScript/Packages/file-upload-with-preview.iife.js'
                );
                $pageRenderer->addCssFile('EXT:creativemuseum/Resources/Public/Css/file-upload-with-preview.css');
            }

        }
    }

    public function initializeAction()
    {
        $formActions = ['saveCampaignAction', 'updateCampaignAction'];

        if (!in_array($this->actionMethodName, $formActions)) {
            return;
        }

        if (!isset($this->arguments['campaignDto'])) {
            return;
        }

        $propertyMapping = $this->arguments['campaignDto']->getPropertyMappingConfiguration();
        $campaignDto = $this->request->getArgument('campaignDto');

        $propertyMapping->allowProperties('badges');
        $propertyMapping->forProperty('badges.*')->allowProperties('id', 'title', 'description');
        $propertyMapping->allowCreationForSubProperty('badges.*');
        $propertyMapping->allowModificationForSubProperty('badges.*');

        $propertyMapping->forProperty('feedbackOptions.*')->allowProperties('id', 'text');
        $propertyMapping->allowCreationForSubProperty('feedbackOptions.*');
        $propertyMapping->allowModificationForSubProperty('feedbackOptions.*');


        if (! empty($campaignDto['start'])) {
            $propertyMapping->forProperty('start')->setTypeConverterOption(
                DateTimeConverter::class,
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                'H:i d-m-Y'
            );
        }

        if (! empty($campaignDto['stop'])) {
            $propertyMapping->forProperty('stop')->setTypeConverterOption(
                DateTimeConverter::class,
                DateTimeConverter::CONFIGURATION_DATE_FORMAT,
                'H:i d-m-Y'
            );
        }
    }

    public function indexAction()
    {
        $campaigns = $this->campaignService->getCampaigns();
        $this->view->assign('campaigns', $campaigns);
    }

    /**
     * @param CampaignDto|null $campaignDto
     * @return void
     */
    public function newCampaignAction(CampaignDto $campaignDto = null)
    {
        $createCampaignLink = $this->getHref('Administration', 'saveCampaign');
        if (null === $campaignDto) {
            $campaignDto = new CampaignDto();
        }
        if ($campaignDto->getFeedbackOptions()->count() < 2) {
            for ($i = $campaignDto->getFeedbackOptions()->count(); $i < 2; $i++) {
                $campaignDto->addFeedbackOption(new FeedbackOptionDto());
            }
        }

        $this->view->assign('campaignDto', $campaignDto);
        $this->view->assign('formUri', $createCampaignLink);
    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function saveCampaignAction(CampaignDto $campaignDto)
    {
        $this->campaignService->createCampaign($campaignDto);
        $this->addFlashMessage('Wuup wuup');
        $this->forward('index');
    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     */
    public function editCampaignAction(CampaignDto $campaignDto)
    {
        $this->view->assign('campaignDto', $campaignDto);

    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function updateCampaignAction(CampaignDto $campaignDto)
    {
        $this->campaignService->updateCampaign($campaignDto);
        $this->addFlashMessage('Kampagne "' . $campaignDto->getTitle() . '" geupdated.');
        $this->forward('index');
    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function hideCampaignAction(CampaignDto $campaignDto)
    {
        $campaignDto->setActive(false);
        $this->addFlashMessage('Kampagne "' . $campaignDto->getTitle() . '" deaktiviert.');
        $this->campaignService->updateCampaign($campaignDto);
        $this->forward('index');
    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     */
    public function unhideCampaignAction(CampaignDto $campaignDto)
    {
        $campaignDto->setActive(true);
        $this->addFlashMessage('Kampagne "' . $campaignDto->getTitle() . '" aktiviert.');

        $this->campaignService->updateCampaign($campaignDto);
        $this->forward('index');
    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     */
    public function deleteCampaignAction(CampaignDto $campaignDto)
    {
        $this->campaignService->removeCampaign($campaignDto);
        $this->addFlashMessage('Die Kampagne "' . $campaignDto->getTitle() . '" wurde gelöscht.');
        $this->forward('index');
    }

    public function userOverviewAction()
    {

    }

    /**
     * @param BackendTemplateView $view
     * @return void
     */
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

    /**
     * @param BackendTemplateView $view
     * @return void
     */
    private function registerDocheaderButtons(BackendTemplateView $view)
    {
        $action = $this->request->getControllerActionName();

        if ($action === 'newCampaign') {
            $this->addNewCampaignSaveButton($view);
        }
        IF ($action === 'editCampaign') {
            $this->addEditCampaignSaveButton($view);
        }
    }

    /**
     * @param string $controller
     * @param string $action
     * @return string
     */
    private function getHref($controller, $action)
    {
        $this->uriBuilder->setRequest($this->request);
        return $this->uriBuilder->reset()->uriFor($action, [], $controller);
    }

    /**
     * @param BackendTemplateView $view
     * @return void
     */
    private function addNewCampaignSaveButton(BackendTemplateView $view)
    {
        $buttonBar = $view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        /** @var InputButton $saveButton */
        $saveButton = $buttonBar->makeButton(InputButton::class);
        $saveButton
            ->setIcon($this->iconFactory->getIcon('actions-document-save', Icon::SIZE_SMALL))
            ->setValue('Kampagne anlegen')
            ->setTitle('Kampagne anlegen')
            ->setName('campaign-save')
            ->setForm('campaign-form')
            ->setShowLabelText(true);

        $buttonBar->addButton($saveButton);

        $this->addCancelButton($buttonBar);
    }

    private function addEditCampaignSaveButton(BackendTemplateView $view)
    {
        $buttonBar = $view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        /** @var InputButton $saveButton */
        $saveButton = $buttonBar->makeButton(InputButton::class);
        $saveButton
            ->setIcon($this->iconFactory->getIcon('actions-document-save', Icon::SIZE_SMALL))
            ->setValue('Kampagne speichern')
            ->setTitle('Kampagne speichern')
            ->setName('campaign-update')
            ->setForm('campaign-form')
            ->setShowLabelText(true);

        $buttonBar->addButton($saveButton);

        $this->addCancelButton($buttonBar);
    }

    /**
     * @param ButtonBar $buttonBar
     * @return void
     */
    private function addCancelButton(ButtonBar $buttonBar)
    {
        /** @var LinkButton $backButton */
        $backButton = $buttonBar->makeButton(LinkButton::class);
        $backButton
            ->setIcon($this->iconFactory->getIcon('actions-close', Icon::SIZE_SMALL))
            ->setTitle('Abbrechen')
            ->setHref($this->getHref('Administration', 'index'))
            ->setShowLabelText(true);

        $buttonBar->addButton($backButton);
    }
}