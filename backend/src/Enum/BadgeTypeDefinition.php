<?php

namespace App\Enum;

use App\Doctrine\Type\AbstractEnumType;

class BadgeTypeDefinition extends AbstractEnumType
{
    public const NAME = 'badgetype';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return BadgeType::class;
    }
}