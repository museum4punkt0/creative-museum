<?php

namespace App\Enum;

use App\Doctrine\Type\AbstractEnumType;

class MailTypeDefinition extends AbstractEnumType
{
    public const NAME = 'mailtype';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return MailType::class;
    }
}