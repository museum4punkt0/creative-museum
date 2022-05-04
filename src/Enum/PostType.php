<?php


namespace App\Enum;

enum PostType: string
{
    case TEXT = 'text';

    case IMAGE = 'image';

    case VIDEO = 'video';

    case AUDIO = 'audio';

    case POLL = 'poll';

    case PLAYLIST = 'playlist';
}