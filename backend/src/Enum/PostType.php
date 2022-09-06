<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Enum;

enum PostType: string
{
    case TEXT = 'text';

    case IMAGE = 'image';

    case VIDEO = 'video';

    case AUDIO = 'audio';

    case POLL = 'poll';

    case PLAYLIST = 'playlist';

    case SYSTEM = 'system';
}
