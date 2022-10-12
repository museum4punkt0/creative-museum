<?php

namespace App\Serializer\Normalizer;

use App\Entity\User;
use App\Service\BadgeService;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct
    (
        private readonly ObjectNormalizer $normalizer,
        private readonly BadgeService $badgeService
    ){}

    public function normalize($object, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $lastBadge = $this->badgeService->getLastBadge($object->getId());
        $data['lastBadge'] = $lastBadge ? $this->normalizer->normalize($lastBadge,$format,$context) : null;

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}