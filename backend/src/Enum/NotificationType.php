<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

enum NotificationType: string
{
    case NONE = '';

    case PERSONAL = 'personal';

    case PLATFORM = 'platform';

    case EDITOR = 'editor';

    case ALL = 'all';
}
