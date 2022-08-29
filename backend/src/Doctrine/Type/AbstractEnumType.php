<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractEnumType extends \Doctrine\DBAL\Types\Type
{
    abstract public static function getEnumsClass(): string;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'TEXT';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \BackedEnum) {
            return $value->value;
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (false === enum_exists($this->getEnumsClass(), true)) {
            throw new \LogicException('This class should be an enum');
        }
        // 🔥 https://www.php.net/manual/en/backedenum.tryfrom.php
        return $this::getEnumsClass()::tryFrom($value);
    }
}
