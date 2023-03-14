<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

enum MailType: string
{
    case AWARD_RECEIVED = 'award_received';

    case POST_COMMENTED = 'post_commented';

    case POST_REPORTED_AUTHOR = 'post_reported_author';

    case POST_REPORTED = 'post_reported';

    case SYSTEM = 'system';

    case POST_MENTION = 'post_mention';

    case COMMENT_MENTION = 'comment_mention';

    case EDITOR = 'editor';
}
