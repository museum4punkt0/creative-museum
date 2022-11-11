<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Controller;

use JWIED\Creativemuseum\Domain\Model\Dto\AwardDto;
use JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto;
use JWIED\Creativemuseum\Domain\Model\Dto\CampaignDto;
use JWIED\Creativemuseum\Domain\Model\Dto\CmsContentDto;
use JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PartnerDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PostDto;
use JWIED\Creativemuseum\Domain\Model\Dto\PostListFilterDto;
use JWIED\Creativemuseum\Domain\Model\Dto\UserDto;
use JWIED\Creativemuseum\Domain\Model\Dto\UserListFilterDto;
use JWIED\Creativemuseum\Pagination\ApiRecordPaginator;
use JWIED\Creativemuseum\Service\CampaignService;
use JWIED\Creativemuseum\Service\CmsContentService;
use JWIED\Creativemuseum\Service\NotificationService;
use JWIED\Creativemuseum\Service\PostService;
use JWIED\Creativemuseum\Service\UserService;
use TYPO3\CMS\Backend\Template\Components\ButtonBar;
use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Backend\Template\Components\Buttons\LinkButton;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Imaging\Icon;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder;
use TYPO3\CMS\Extbase\Property\TypeConverter\DateTimeConverter;
use TYPO3\CMS\Extbase\Property\TypeConverter\PersistentObjectConverter;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
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
     * @var PostService
     */
    protected $postService;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @var NotificationService
     */
    protected $notificationService;

    /**
     * @var CmsContentService
     */
    protected $cmsContentService;

    /**
     * @var IconFactory
     */
    protected $iconFactory;

    /**
     * @var AssetCollector
     */
    protected $assetCollector;


    public function __construct(
        UriBuilder $uriBuilder,
        CampaignService $campaignService,
        PostService $postService,
        UserService $userService,
        NotificationService $notificationService,
        CmsContentService $cmsContentService,
        IconFactory $iconFactory,
        AssetCollector $assetCollector
    ) {
        $this->uriBuilder = $uriBuilder;
        $this->campaignService = $campaignService;
        $this->postService = $postService;
        $this->userService = $userService;
        $this->notificationService = $notificationService;
        $this->cmsContentService = $cmsContentService;
        $this->iconFactory = $iconFactory;
        $this->assetCollector = $assetCollector;

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

            if ($this->actionMethodName === 'cmsContentAction') {
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Creativemuseum/CmsContentHandling');
            }

            if ($this->actionMethodName === 'notificationUserCreateAction') {
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Creativemuseum/UserSearchAutocomplete');
                $pageRenderer->addCssFile('EXT:creativemuseum/Resources/Public/Css/livesearch.css');
            }

            if (in_array($this->actionMethodName, ['newCampaignAction', 'editCampaignAction'])) {
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Creativemuseum/CampaignHandling');
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Backend/ColorPicker');

                $pageRenderer->addJsFile(
                    'EXT:creativemuseum/Resources/Public/JavaScript/Packages/file-upload-with-preview.iife.js'
                );
                $pageRenderer->addCssFile('EXT:creativemuseum/Resources/Public/Css/file-upload-with-preview.css');
            }
            if ($this->actionMethodName === 'userDetailAction') {
                $pageRenderer->loadRequireJsModule('TYPO3/CMS/Creativemuseum/UserHandling');
            }

        }
    }

    public function initializeAction()
    {
        $userActions = ['userOverviewAction', 'userDetailAction', 'toggleUserActiveAction', 'deleteUserAction'];

        if (in_array($this->actionMethodName, $userActions)) {
            $propertyMapping = $this->arguments['filter']->getPropertyMappingConfiguration();
            $propertyMapping->allowProperties('page', 'searchString');
            $propertyMapping->setTypeConverterOption(
                PersistentObjectConverter::class,
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                true
            );
        }

        $postActions = ['postOverviewAction', 'postDetailAction', 'deletePostAction'];

        if (in_array($this->actionMethodName, $postActions)) {
            $propertyMapping = $this->arguments['filter']->getPropertyMappingConfiguration();
            $propertyMapping->allowProperties('page', 'searchString', 'reported');
            $propertyMapping->setTypeConverterOption(
                PersistentObjectConverter::class,
                PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED,
                true
            );
        }

        $formActions = ['saveCampaignAction', 'updateCampaignAction'];

        if (in_array($this->actionMethodName, ['newCampaignAction', 'editCampaignAction'])) {
            $this->assetCollector->addInlineJavaScript(
                'creativeMuseumApiUrls',
                'window.apiBaseUrl = "' . $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['creativemuseum']['baseUrl'] . '";'
            );
        }

        if (!in_array($this->actionMethodName, $formActions)) {
            return;
        }

        if (!isset($this->arguments['campaignDto'])) {
            return;
        }

        $propertyMapping = $this->arguments['campaignDto']->getPropertyMappingConfiguration();
        $campaignDto = $this->request->getArgument('campaignDto');

        $propertyMapping->allowProperties('badges');
        $propertyMapping
            ->forProperty('badges.*')
            ->allowProperties('id', 'title', 'description', 'badgeType', 'pictureIRI', 'threshold','shortDescription','link');
        $propertyMapping->allowCreationForSubProperty('badges.*');
        $propertyMapping->allowModificationForSubProperty('badges.*');

        $propertyMapping->allowProperties('awards');
        $propertyMapping
            ->forProperty('awards.*')
            ->allowProperties('id', 'title', 'description', 'pictureIRI', 'price','link');
        $propertyMapping->allowCreationForSubProperty('awards.*');
        $propertyMapping->allowModificationForSubProperty('awards.*');

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

    public function notificationIndexAction()
    {

    }

    public function notificationGlobalCreateAction()
    {

    }

    public function notificationGlobalSendAction(string $message)
    {
        $success = $this->notificationService->sendGlobalNotification($message);
        $this->addFlashMessage($success ? 'Benachrichtigung erfolgreich versendet' : 'Es ist ein Fehler aufgetreten.');
        $this->redirect('notificationIndex');
    }

    public function notificationCampaignCreateAction()
    {
        $campaigns = $this->campaignService->getCampaigns();
        $this->view->assign('campaigns', $campaigns);
    }

    public function notificationCampaignSendAction(int $campaign, string $message)
    {
        $success = $this->notificationService->sendCampaignNotification($campaign, $message);
        $this->addFlashMessage($success ? 'Benachrichtigung erfolgreich versendet' : 'Es ist ein Fehler aufgetreten.');
        $this->redirect('notificationIndex');
    }

    public function notificationUserCreateAction()
    {

    }

    public function notificationUserSendAction(string $user, string $message)
    {
        $success = $this->notificationService->sendUserNotification($user, $message);
        $this->addFlashMessage($success ? 'Benachrichtigung erfolgreich versendet' : 'Es ist ein Fehler aufgetreten.');
        $this->redirect('notificationIndex');
    }

    public function cmsContentAction()
    {
        $about = $this->cmsContentService->getContentForIdentifier('about');
        $faq = $this->cmsContentService->getContentForIdentifier('faq');
        $simpleLanguage = $this->cmsContentService->getContentForIdentifier('simpleLanguage');
        $signLanguage = $this->cmsContentService->getContentForIdentifier('signLanguage');
        $imprint = $this->cmsContentService->getContentForIdentifier('imprint');

        $cmsContentDto = new CmsContentDto($about, $simpleLanguage, $signLanguage, $faq, $imprint);

        $this->view->assign('cmsContentDto', $cmsContentDto);
    }

    public function cmsContentUpdateAction(CmsContentDto $cmsContentDto)
    {
        $this->cmsContentService->updateContent($cmsContentDto);
        $this->addFlashMessage('Inhalte erfolgreich aktualisiert');
        $this->redirect('cmsContent');
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

        if ($campaignDto->getBadges()->count() === 0) {
            $campaignDto->addBadge(new BadgeDto());
        }
        if ($campaignDto->getAwards()->count() === 0) {
            $campaignDto->addAward(new AwardDto());
        }

        if ($campaignDto->getPartners()->count() === 0) {
            $campaignDto->addPartner(new PartnerDto());
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
        $this->addFlashMessage('Kampagne "' . $campaignDto->getTitle() . '" erstellt.');
        $this->forward('index');
    }

    /**
     * @param CampaignDto $campaignDto
     * @return void
     */
    public function editCampaignAction(CampaignDto $campaignDto)
    {
        if ($campaignDto->getBadges()->count() === 0) {
            $campaignDto->addBadge(new BadgeDto());
        }
        if ($campaignDto->getAwards()->count() === 0) {
            $campaignDto->addAward(new AwardDto());
        }
        if ($campaignDto->getPartners()->count() === 0) {
            $campaignDto->addPartner(new PartnerDto());
        }

        if ($campaignDto->getFeedbackOptions()->count() < 2) {
            for ($i = $campaignDto->getFeedbackOptions()->count(); $i < 2; $i++) {
                $campaignDto->addFeedbackOption(new FeedbackOptionDto());
            }
        }

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

    /**
     * @param UserListFilterDto|null $filter
     * @return void
     */
    public function userOverviewAction(?UserListFilterDto $filter = null)
    {
        if (null === $filter) {
            $filter = new UserListFilterDto();
        }

        $users = $this->userService->getUsers($filter);

        $paginator = new ApiRecordPaginator(
            $users['hydra:member'],
            $filter->getPage(),
            UserService::RECORDS_PER_PAGE,
            $users['hydra:totalItems']
        );

        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple(
            [
                'items' => $users,
                'paginator' => $paginator,
                'paging' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'actionName' => substr($this->actionMethodName, 0, -6),
                'filter' => $filter
            ]
        );
    }

    /**
     * @param UserDto $user
     * @param UserListFilterDto|null $filter
     * @return void
     */
    public function userDetailAction(UserDto $user, ?UserListFilterDto $filter = null): void
    {
        if (null === $filter) {
            $filter = new UserListFilterDto();
        }

        $this->view->assignMultiple([
            'user' => $user,
            'filter' => $filter
        ]);
    }

    /**
     * @param UserDto $user
     * @param UserListFilterDto|null $filter
     * @return void
     */
    public function toggleUserActiveAction(UserDto $user, ?UserListFilterDto $filter = null): void
    {
        if (null === $filter) {
            $filter = new UserListFilterDto();
        }

        $this->userService->updateUser($user->getUuid(), ['active' => !$user->isActive()]);

        $newStateActive = $user->isActive() ? 'deaktiviert' : 'aktiviert';

        $this->addFlashMessage('User erfolgreich ' . $newStateActive);

        $this->redirect('userDetail', null, null, [
            'user' => $user->getUuid(),
            'filter' => ['page' => $filter->getPage(), 'searchString' => $filter->getSearchString()]
        ]);
    }

    /**
     * @param UserDto $user
     * @param UserListFilterDto|null $filter
     * @return void
     */
    public function deleteUserAction(UserDto $user, UserListFilterDto $filter = null): void
    {
        if (null === $filter) {
            $filter = new UserListFilterDto();
        }

        $this->addFlashMessage('Benutzer erfolgreich gelöscht');

        $this->userService->deleteUser($user->getUuid());

        $this->redirect('userOverview', null, null, [
            'filter' => ['page' => $filter->getPage(), 'searchString' => $filter->getSearchString()]
        ]);
    }

    /**
     * @param PostListFilterDto|null $filter
     * @return void
     */
    public function postOverviewAction(PostListFilterDto $filter = null): void
    {
        if (null === $filter) {
            $filter = new PostListFilterDto();
        }

        $posts = $this->postService->getPosts($filter);

        $paginator = new ApiRecordPaginator(
            $posts['hydra:member'],
            $filter->getPage(),
            PostService::RECORDS_PER_PAGE,
            $posts['hydra:totalItems']
        );

        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple(
            [
                'items' => $posts,
                'paginator' => $paginator,
                'paging' => $pagination,
                'pages' => range(1, $pagination->getLastPageNumber()),
                'actionName' => substr($this->actionMethodName, 0, -6),
                'filter' => $filter
            ]
        );
    }

    /**
     * @param PostDto $post
     * @param PostListFilterDto $filter
     * @param int|null $page
     * @return void
     */
    public function postDetailAction(PostDto $post, PostListFilterDto $filter, ?int $page = 1): void
    {
        $comments = $this->postService->getComments($post, $page) ?? ['hydra:member' => [], 'hydra:totalItems' => 0];

        $paginator = new ApiRecordPaginator(
            $comments['hydra:member'],
            $page,
            PostService::RECORDS_PER_PAGE,
            $comments['hydra:totalItems']
        );

        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple([
            'post' => $post,
            'filter' => $filter,
            'comments' => $comments,
            'paginator' => $paginator,
            'paging' => $pagination,
            'pages' => range(1, $pagination->getLastPageNumber()),
            'actionName' => substr($this->actionMethodName, 0, -6),
            'page' => $page,
            'backendBaseUrl' => $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['creativemuseum']['baseUrl']
        ]);
    }

    /**
     * @param PostDto $post
     * @param PostListFilterDto|null $filter
     * @return void
     */
    public function deletePostAction(PostDto $post, PostListFilterDto $filter = null): void
    {
        if (null === $filter) {
            $filter = new PostListFilterDto();
        }

        $this->addFlashMessage('Post erfolgreich gelöscht');

        $this->postService->deletePost($post->getId());

        $filters = ['page' => $filter->getPage(), 'searchString' => $filter->getSearchString()];

        if ($filter->isReported()) {
            $filters['reported'] = 1;
        }

        $this->redirect('postOverview', null, null, [
            'filter' => $filters
        ]);
    }

    /**
     * @param BackendTemplateView $view
     * @return void
     */
    private function generateMenu(BackendTemplateView $view): void
    {
        $menuRegistry = $view->getModuleTemplate()->getDocHeaderComponent()->getMenuRegistry();
        $menu = $menuRegistry->makeMenu();
        $menu->setIdentifier('creativeMuseumModuleMenu');

        $menuItems = ['Administration' => ['index', 'userOverview', 'postOverview', 'notificationIndex', 'cmsContent']];

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
                    $tmpAction = $this->request->getControllerActionName();
                    if ($tmpAction === 'userDetail') {
                        $tmpAction = 'userOverview';
                    }
                    if ($tmpAction === 'postDetail') {
                        $tmpAction = 'postOverview';
                    }
                    $isActive = $action === $tmpAction ? true : false;
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
    private function registerDocheaderButtons(BackendTemplateView $view): void
    {
        $action = $this->request->getControllerActionName();

        if ($action === 'newCampaign') {
            $this->addNewCampaignSaveButton($view);
        }
        if ($action === 'editCampaign') {
            $this->addEditCampaignSaveButton($view);
        }
        if ($action === 'userDetail') {
            $this->addUserDetailButtons($view);
        }
        if ($action === 'postDetail') {
            $this->addPostDetailButtons($view);
        }
        if ($action === 'cmsContent') {
            $this->addCmsContentButtons($view);
        }
    }

    /**
     * @param string $controller
     * @param string $action
     * @return string
     */
    private function getHref($controller, $action): string
    {
        $this->uriBuilder->setRequest($this->request);
        return $this->uriBuilder->reset()->uriFor($action, [], $controller);
    }

    /**
     * @param BackendTemplateView $view
     * @return void
     */
    private function addNewCampaignSaveButton(BackendTemplateView $view): void
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

    private function addUserDetailButtons(BackendTemplateView $view)
    {
        $arguments = $this->request->getArgument('filter');
        $uuid = $this->request->getArgument('user');

        $uriArgs  = '&tx_creativemuseum_system_creativemuseumcmadm[filter][page]=' . $arguments['page'];
        $uriArgs .= '&tx_creativemuseum_system_creativemuseumcmadm[filter][searchString]=' . $arguments['searchString'];

        $buttonBar = $view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        $backButton = $buttonBar->makeButton(LinkButton::class);
        $backButton
            ->setIcon($this->iconFactory->getIcon('actions-close', Icon::SIZE_SMALL))
            ->setTitle('zurück')
            ->setHref(
                $this->getHref(
                    'Administration',
                    'userOverview'
                ) . $uriArgs
            )
            ->setShowLabelText(true);

        $uriArgs .= '&tx_creativemuseum_system_creativemuseumcmadm[user]=' . $uuid;

        $enableDisableButton = $buttonBar->makeButton(LinkButton::class);
        $enableDisableButton
            ->setIcon($this->iconFactory->getIcon('actions-eye', Icon::SIZE_SMALL))
            ->setTitle('De-/Aktivieren')
            ->setHref(
                $this->getHref(
                    'Administration',
                    'toggleUserActive'
                ) . $uriArgs
            )
            ->setShowLabelText(true);

        $deleteButton = $buttonBar->makeButton(LinkButton::class);
        $deleteButton
            ->setIcon($this->iconFactory->getIcon('actions-delete', Icon::SIZE_SMALL))
            ->setTitle('Löschen')
            ->setHref(
                $this->getHref(
                    'Administration',
                    'deleteUser'
                ) . $uriArgs
            )
            ->setDataAttributes([
                'delete-user-button' => '',
                'title' => 'Benutzer löschen?',
                'message' => 'Soll der Benutzer wirklich gelöscht werden?',
                'button-close-text' => 'Abbrechen',
                'button-ok-text' => 'Ja, Benutzer löschen'
            ])
            ->setShowLabelText(true);

        $buttonBar->addButton($backButton);
        $buttonBar->addButton($enableDisableButton);
        $buttonBar->addButton($deleteButton);
    }

    private function addPostDetailButtons(BackendTemplateView $view)
    {
        $arguments = $this->request->getArgument('filter');
        $id = $this->request->getArgument('post');

        $uriArgs  = '&tx_creativemuseum_system_creativemuseumcmadm[filter][page]=' . $arguments['page'];
        $uriArgs .= '&tx_creativemuseum_system_creativemuseumcmadm[filter][searchString]=' . $arguments['searchString'];

        if (isset($arguments['reported']) && (int) $arguments['reported'] === 1) {
            $uriArgs .= '&tx_creativemuseum_system_creativemuseumcmadm[filter][reported]=1';
        }

        $buttonBar = $view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        /** @var LinkButton $backButton */
        $backButton = $buttonBar->makeButton(LinkButton::class);
        $backButton
            ->setIcon($this->iconFactory->getIcon('actions-close', Icon::SIZE_SMALL))
            ->setTitle('zurück')
            ->setHref(
                $this->getHref(
                    'Administration',
                    'postOverview'
                ) . $uriArgs
            )
            ->setShowLabelText(true);

        $uriArgs .= '&tx_creativemuseum_system_creativemuseumcmadm[post]=' . $id;

        $deleteButton = $buttonBar->makeButton(LinkButton::class);
        $deleteButton
            ->setIcon($this->iconFactory->getIcon('actions-delete', Icon::SIZE_SMALL))
            ->setTitle('Löschen')
            ->setClasses('t3js-record-delete')
            ->setHref(
                $this->getHref(
                    'Administration',
                    'deletePost'
                ) . $uriArgs
            )
            ->setDataAttributes([
                'delete-user-button' => '',
                'title' => 'Beitrag löschen?',
                'message' => 'Soll der Beitrag wirklich gelöscht werden?',
                'button-close-text' => 'Abbrechen',
                'button-ok-text' => 'Ja, Beitrag löschen'
            ])
            ->setShowLabelText(true);

        $buttonBar->addButton($backButton);
        $buttonBar->addButton($deleteButton);
    }

    private function addCmsContentButtons(BackendTemplateView $view)
    {
        $buttonBar = $view->getModuleTemplate()->getDocHeaderComponent()->getButtonBar();

        /** @var InputButton $saveButton */
        $saveButton = $buttonBar->makeButton(InputButton::class);
        $saveButton
            ->setIcon($this->iconFactory->getIcon('actions-document-save', Icon::SIZE_SMALL))
            ->setValue('Inhalte speichern')
            ->setTitle('Inhalte speichern')
            ->setName('cms-content-update')
            ->setForm('cms-content-form')
            ->setShowLabelText(true);

        $buttonBar->addButton($saveButton);
    }

    /**
     * @param ButtonBar $buttonBar
     * @return void
     */
    private function addCancelButton(ButtonBar $buttonBar): void
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