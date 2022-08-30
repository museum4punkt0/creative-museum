<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Serializer\Normalizer;

use App\Entity\Award;
use App\Entity\Awarded;
use App\Repository\AwardedRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class AwardNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
        private Security $security,
        private AwardedRepository $awardedRepository,
    ) {}

    public function normalize($object, $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        if (null === $this->security->getUser()) {
            return $data;
        }

        $awarded = $this->awardedRepository->findOneBy([
            'award' => $object,
            'giver' => $this->security->getUser()
        ]);

        if ($awarded instanceof Awarded) {
            $data['taken'] = true;
        }

        return $data;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Award;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
