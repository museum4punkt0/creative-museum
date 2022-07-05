<?php

namespace App\Enum;

use App\Doctrine\Type\AbstractEnumType;

class VoteDirectionDefinition extends AbstractEnumType
{
    public const NAME = 'votedirection';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return VoteDirection::class;
    }
}