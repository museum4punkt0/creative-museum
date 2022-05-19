<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class EnumNormalizer implements NormalizerInterface, DenormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array()) {
        /** @var \BackedEnum $object */
        return $object();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null) {
        return is_object($data) && enum_exists($data::class);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array()) {
        return new $class($data);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null) {
        return is_object($data) && is_subclass_of($type, \BackedEnum::class);
    }

    public function hasCacheableSupportsMethod(): bool {
        return __CLASS__ === \get_class($this);
    }
}