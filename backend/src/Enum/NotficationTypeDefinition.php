<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

use App\Doctrine\Type\AbstractEnumType;

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
