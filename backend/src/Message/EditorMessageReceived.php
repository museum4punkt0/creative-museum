<?php
declare(strict_types=1);

namespace App\Message;

class EditorMessageReceived
{
    private string $message;

    private ?int $campaignId;

    private ?string $userUuid;

    public function __construct(string $message, ?int $campaignId = null, ?string $userUuid = null)
    {
        $this->message = $message;
        $this->campaignId = $campaignId;
        $this->userUuid = $userUuid;
    }

    /**
     * @return int|null
     */
    public function getCampaignId(): ?int
    {
        return $this->campaignId;
    }

    /**
     * @return string|null
     */
    public function getUserUuid(): ?string
    {
        return $this->userUuid;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}