<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CmApiService implements SingletonInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * @param ExtensionConfiguration|null $extensionConfiguration
     */
    public function __construct(ExtensionConfiguration $extensionConfiguration = null)
    {
        $extensionConfiguration = $extensionConfiguration ?: GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $this->configuration = $extensionConfiguration->get('creativemuseum');
    }


    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser()
    {
        return $GLOBALS['BE_USER'];
    }
}