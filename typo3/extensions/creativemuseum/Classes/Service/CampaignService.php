<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use TYPO3\CMS\Core\SingletonInterface;

class CampaignService implements SingletonInterface
{

    private function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}