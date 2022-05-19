<?php

namespace App\Enum;

enum NotificationType: string
{
    case NONE = '';

    case PERSONAL = 'personal';

    case PLATFORM = 'platform';

    case ALL = 'all';
}