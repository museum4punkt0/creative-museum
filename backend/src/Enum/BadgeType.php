<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

enum BadgeType: string
{
    case AWARDS = 'awards';

    case SCORING = 'scoring';

    case POSTCOUNT = 'postcount';

    case LIKES = 'likes';

    case REWARD_POINTS = 'reward_points';
}
