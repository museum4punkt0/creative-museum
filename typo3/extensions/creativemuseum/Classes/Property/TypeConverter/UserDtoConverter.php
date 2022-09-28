<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Property\TypeConverter;
use JWIED\Creativemuseum\Domain\Model\Dto\UserDto;
use JWIED\Creativemuseum\Service\UserService;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverterInterface;

class UserDtoConverter extends AbstractApiObjectConverter implements TypeConverterInterface
{
    /**
     * @var UserService
     */
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getSupportedSourceTypes(): array
    {
        return ['string'];
    }

    public function getSupportedTargetType(): string
    {
        return UserDto::class;
    }

    public function getTargetTypeForSource(
        $source,
        string $originalTargetType,
        PropertyMappingConfigurationInterface $configuration = null
    ): string {
        return UserDto::class;
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
        $user = $this->userService->getUser((string) $source);
        return $this->userService->convert($user);
    }
}