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
use App\Entity\CampaignMember;
use App\Entity\User;
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
        /** @var User $user */
        $user = $this->security->getUser();

        if (null === $user) {
            return $data;
        }

        $memberships = $user->getMemberships()->filter(function ($membership) {
            /** @var CampaignMember $membership */
            /** @var Award $object */
            return $membership->getCampaign()->getId() === $object->getCampaign()->getId();
        });

        if (count($memberships) > 0) {
            /** @var CampaignMember $membership */
            $membership = $memberships[0];
            $data['available'] = $membership->getScore() > $object->getPrice();
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
