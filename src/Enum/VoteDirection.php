<?php


namespace App\Entity;

enum VoteDirection: string
{
    case UP = 'up';

    case DOWN = 'down';
}