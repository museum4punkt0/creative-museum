<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
