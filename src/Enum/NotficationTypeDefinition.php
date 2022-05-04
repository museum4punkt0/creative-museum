<?php

namespace App\Enum;

use App\DoctrineType\AbstractEnumType;

class NotficationTypeDefinition extends AbstractEnumType
{
    public const NAME = 'notficationtype';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return NotificationType::class;
    }
}