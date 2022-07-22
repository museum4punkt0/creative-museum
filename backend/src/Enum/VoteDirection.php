<?php


namespace App\Enum;

enum VoteDirection: string
{
    case UP = 'up';

    case DOWN = 'down';

    case NONE = 'none';
}