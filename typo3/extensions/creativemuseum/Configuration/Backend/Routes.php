<?php

return [
    'system_CreativemuseumCmAdm.campaignOverview' => [
        'path' => '/module/system/CreativemuseumCmAdm/campaignOverview',
        'target' => JWIED\Creativemuseum\Controller\AmdinistrationController::class . '::indexAction'
    ],
    'system_CreativemuseumCmAdm.userOverview' => [
        'path' => '/module/system/CreativemuseumCmAdm/userOverview',
        'referrer' => 'required,refresh-always',
        'target' => JWIED\Creativemuseum\Controller\AdministrationController::class . '::userOverviewAction'
    ],
];