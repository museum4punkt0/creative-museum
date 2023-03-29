<?php

namespace JWIED\Creativemuseum\ViewHelpers;

use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;

final class IsAdminViewHelper extends AbstractConditionViewHelper
{
    public static function verdict(array $arguments, RenderingContextInterface $renderingContext)
    {
        $context = GeneralUtility::makeInstance(Context::class);

        return $context->getPropertyFromAspect('backend.user', 'isAdmin');
    }
}