<?php

use JWIED\Creativemuseum\Controller;

/**
 * Definitions for routes provided by EXT:backend
 * Contains all "regular" routes for entry points
 *
 * Please note that this setup is preliminary until all Core use-cases are set up here.
 * Especially some more properties regarding modules will be added until TYPO3 CMS 7 LTS, and might change.
 *
 * Currently the "access" property is only used so no token creation + validation is made,
 * but will be extended further.
 */
return [
    'cm_ajax_uploader' => [
        'path' => '/cm_ajax_uploader',
        'referrer' => 'required',
        'target' => Controller\UploadController::class . '::uploadAction'
    ],
    'cm_ajax_usersearch' => [
        'path' => '/cm_ajax_usersearch',
        'referrer' => 'required',
        'target' => Controller\AjaxUserSearchController::class . '::searchAction'
    ]
];