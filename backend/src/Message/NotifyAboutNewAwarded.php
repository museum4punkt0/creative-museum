<?php

namespace App\Message;

class NotifyAboutNewAwarded
{
    private int $awardedId;

    public function __construct(int $awardedId)
    {
        $this->awardedId = $awardedId;
    }

    public function getAwardedId(): int
    {
        return $this->awardedId;
    }
}