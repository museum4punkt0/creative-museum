<?php

use JWIED\Creativemuseum\Controller\AdministrationController;

if (TYPO3_MODE === 'BE') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'JWIED.Creativemuseum',
        'system',
        'cm_adm',
        '',
        [
            AdministrationController::class => 'index, userOverview',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:creativemuseum/ext_icon.png',
            'labels' => 'LLL:EXT:creativemuseum/Resources/Private/Language/locallang_mod.xlf',
        ],
    );
}