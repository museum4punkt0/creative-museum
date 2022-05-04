<?php

namespace App\Entity;

use App\DoctrineType\AbstractEnumType;

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