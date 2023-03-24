<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Message;

class EditorMessageReceived
{
    private string $message;

    private string $headline;

    private ?int $campaignId;

    private ?string $userUuid;

    public function __construct(string $message, string $headline, ?int $campaignId = null, ?string $userUuid = null)
    {
        $this->message = $message;
        $this->campaignId = $campaignId;
        $this->userUuid = $userUuid;
        $this->headline = $headline;
    }

    public function getCampaignId(): ?int
    {
        return $this->campaignId;
    }

    public function getUserUuid(): ?string
    {
        return $this->userUuid;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }
}
