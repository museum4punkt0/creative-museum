<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
