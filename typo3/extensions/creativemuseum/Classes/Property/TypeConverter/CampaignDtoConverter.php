<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Property\TypeConverter;

use JWIED\Creativemuseum\Domain\Model\Dto\CampaignDto;
use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverterInterface;

class CampaignDtoConverter extends AbstractApiObjectConverter implements TypeConverterInterface
{
    /**
     * @var CampaignService
     */
    private CampaignService $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function getSupportedSourceTypes(): array
    {
        return ['integer', 'string'];
    }

    public function getSupportedTargetType(): string
    {
        return CampaignDto::class;
    }

    public function getTargetTypeForSource(
        $source,
        string $originalTargetType,
        PropertyMappingConfigurationInterface $configuration = null
    ): string {
        return CampaignDto::class;
    }

    public function getPriority(): int
    {
        return 500;
    }

    public function canConvertFrom($source, string $targetType): bool
    {
        return true;
    }

    public function getSourceChildPropertiesToBeConverted($source): array
    {
        return [];
    }

    public function getTypeOfChildProperty(
        string $targetType,
        string $propertyName,
        PropertyMappingConfigurationInterface $configuration
    ): string {
        return '';
    }

    public function convertFrom(
        $source,
        string $targetType,
        array $convertedChildProperties = [],
        PropertyMappingConfigurationInterface $configuration = null
    ) {
        $campaign = $this->campaignService->getCampaign((int) $source);
        return $this->campaignService->convert($campaign);
    }
}