<?php

namespace App\Enum;

enum MailType: string
{
    case AWARD_RECEIVED = 'award_received';

    case POST_COMMENTED = 'post_commented';

    CASE POST_REPORTED_AUTHOR = 'post_reported_author';

    CASE POST_REPORTED = 'post_reported';

    case SYSTEM = 'system';
}