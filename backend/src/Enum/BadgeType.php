<?php

namespace App\Enum;

enum BadgeType: string
{
    case AWARDS = 'awards';

    case SCORING = 'scoring';

    case POSTCOUNT = 'postcount';

    case LIKES = 'likes';

    case REWARD_POINTS = 'reward_points';
}