<?php

namespace App\Enum;

use App\DoctrineType\AbstractEnumType;

class PostTypeDefinition extends AbstractEnumType
{
    public const NAME = 'posttype';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return PostType::class;
    }
}