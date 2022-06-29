<?php

namespace App\Serializer\Normalizer;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use ApiPlatform\Core\Hal\Serializer\CollectionNormalizer;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PostNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @var ObjectNormalizer
     */
    private ObjectNormalizer $normalizer;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    public function __construct(ObjectNormalizer $normalizer, PostRepository $postRepository)
    {
        $this->normalizer = $normalizer;
        $this->postRepository = $postRepository;
    }

    public function normalize($object, $format = null, array $context = array()): array
    {
        $postId = $object->getId();
        $data = $this->normalizer->normalize($object, $format, $context);
        $comments = $this->postRepository->getRecentPostComments($postId);

        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $data['comments'][] = $this->normalizer->normalize($comment, $format, $context);
            }
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Post;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
