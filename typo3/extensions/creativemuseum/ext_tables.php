<?php

use JWIED\Creativemuseum\Controller\AdministrationController;

if (TYPO3_MODE === 'BE') {

    $actions = [
        'index',
        'newCampaign',
        'saveCampaign',
        'editCampaign',
        'updateCampaign',
        'userOverview',
        'hideCampaign',
        'unhideCampaign',
        'deleteCampaign'
    ];

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'JWIED.Creativemuseum',
        'system',
        'cm_adm',
        '',
        [
            AdministrationController::class => implode(',', $actions),
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
}