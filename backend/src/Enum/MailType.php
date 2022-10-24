<?php

namespace App\Enum;

enum MailType: string
{
    case AWARD_RECEIVED = 'award_received';

    case BADGE_RECEIVED = 'badge_received';

    case NEW_CAMPAIGN = 'new_campaign';

    case CAMPAIGN_CLOSED = 'campaign_closed';

    case POST_COMMENTED = 'post_commented';

    case SYSTEM = 'system';
}