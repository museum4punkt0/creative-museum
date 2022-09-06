<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

enum PointsReceivedType: string
{
    case REGISTRATION = 'registration';

    case LOGIN = 'login';

    case AWARDED = 'awarded';

    case AWARD_RECEIVED = 'award_received';

    case POST_CREATED = 'post_created';

    case COMMENT_CREATED = 'comment_created';

    case UPVOTE = 'upvote';

    case FEEDBACK = 'feedback';
}
