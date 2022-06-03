<?php

namespace App\Message;


class NotifyUserAboutNewAwarded
{
    private int $awardedId;

    public function __construct(int $awardedId)
    {
        $this->awardedId = $awardedId;
    }

    /**
     * @return int
     */
    public function getAwardedId(): int
    {
        return $this->awardedId;
    }
}