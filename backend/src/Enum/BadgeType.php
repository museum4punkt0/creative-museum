<?php

namespace App\Enum;

enum BadgeType: string
{
    case SCORING = 'scoring';

    case POSTCOUNT = 'postcount';

    case LIKES = 'likes';
}