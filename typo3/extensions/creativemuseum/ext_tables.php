<?php

if (TYPO3_MODE === 'BE') {

    $actions = [
        'index',
        'newCampaign',
        'saveCampaign',
        'editCampaign',
        'updateCampaign',
        'hideCampaign',
        'unhideCampaign',
        'deleteCampaign',
        'userOverview',
        'userDetail',
        'toggleUserActive',
        'deleteUser'
    ];

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'JWIED.Creativemuseum',
        'system',
        'cm_adm',
        '',
        [
            \JWIED\Creativemuseum\Controller\AdministrationController::class => implode(',', $actions),
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:creativemuseum/ext_icon.png',
            'labels' => 'LLL:EXT:creativemuseum/Resources/Private/Language/locallang_mod.xlf',
        ],
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
        \JWIED\Creativemuseum\Property\TypeConverter\CampaignDtoConverter::class
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerTypeConverter(
        \JWIED\Creativemuseum\Property\TypeConverter\UserDtoConverter::class
    );
}