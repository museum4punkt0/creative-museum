<?php

namespace App\Enum;

enum FileType: string
{
    case IMAGE = 'image';

    case AUDIO = 'audio';

    case VIDEO = 'video';
}