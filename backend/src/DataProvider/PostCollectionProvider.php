<?php

namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Post;
use App\Repository\PostRepository;

final class PostCollectionProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Post::class === $resourceClass;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        return $this->postRepository->findBy([
            'parent' => null
        ]);

    }
}