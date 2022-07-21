<?php

namespace App\Serializer\Normalizer;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
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

    /**
     * @var Security
     */
    private Security $security;

    public function __construct(
        ObjectNormalizer $normalizer,
        PostRepository $postRepository,
        Security $security
    ) {
        $this->normalizer = $normalizer;
        $this->postRepository = $postRepository;
        $this->security = $security;
    }

    public function normalize($object, $format = null, array $context = array()): array
    {
        $postId = $object->getId();
        $data = $this->normalizer->normalize($object, $format, $context);
        $comments = $this->postRepository->getRecentPostComments($postId);

        if (!empty($comments)) {
            $data['comments'] = [];
            foreach ($comments as $comment) {
                array_unshift($data['comments'],$this->normalizer->normalize($comment, $format, $context));
            }
        }

        if (null !== $this->security->getUser()) {
            /** @var User $user */
            $user = $this->security->getUser();

            if ($user->getBookmarks()->contains($object)) {
                $data['bookmarked'] = true;
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
