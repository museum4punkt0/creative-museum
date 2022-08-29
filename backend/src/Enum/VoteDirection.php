<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

enum VoteDirection: string
{
    case UP = 'up';

    case DOWN = 'down';

    case NONE = 'none';
}
