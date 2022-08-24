<?php

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