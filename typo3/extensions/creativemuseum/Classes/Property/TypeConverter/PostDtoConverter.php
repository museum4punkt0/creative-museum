<?php

namespace JWIED\Creativemuseum\Property\TypeConverter;

use JWIED\Creativemuseum\Domain\Model\Dto\PostDto;
use JWIED\Creativemuseum\Service\PostService;
use TYPO3\CMS\Extbase\Property\PropertyMappingConfigurationInterface;
use TYPO3\CMS\Extbase\Property\TypeConverterInterface;

class PostDtoConverter extends AbstractApiObjectConverter implements TypeConverterInterface
{
    /**
     * @var PostService
     */
    private PostService $postService;

    /**
     * @param PostService $postService
     */
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function getSupportedSourceTypes(): array
    {
        return ['integer', 'string'];
    }

    public function getSupportedTargetType(): string
    {
        return PostDto::class;
    }

    public function getTargetTypeForSource($source, string $originalTargetType, PropertyMappingConfigurationInterface $configuration = null): string
    {
        return PostDto::class;
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

    public function getTypeOfChildProperty(string $targetType, string $propertyName, PropertyMappingConfigurationInterface $configuration): string
    {
        return '';
    }

    public function convertFrom($source, string $targetType, array $convertedChildProperties = [], PropertyMappingConfigurationInterface $configuration = null)
    {
        $post = $this->postService->getPost((string) $source);
        return $this->postService->convert($post);
    }
}