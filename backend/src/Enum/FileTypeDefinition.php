<?php

namespace App\Enum;

use App\Doctrine\Type\AbstractEnumType;

class FileTypeDefinition extends AbstractEnumType
{
    public const NAME = 'filetype';

    public function getName(): string
    {
        return self::NAME;
    }

    public static function getEnumsClass(): string
    {
        return FileType::class;
    }
}