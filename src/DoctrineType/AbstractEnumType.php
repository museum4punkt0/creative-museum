<?php

namespace App\DoctrineType;

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
            throw new \LogicException("This class should be an enum");
        }
        // 🔥 https://www.php.net/manual/en/backedenum.tryfrom.php
        return $this::getEnumsClass()::tryFrom($value);
    }
}