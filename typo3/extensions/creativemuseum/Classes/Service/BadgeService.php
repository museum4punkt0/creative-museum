<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto;

class BadgeService extends CmApiService
{
    const ENDPOINT = 'v1/badges';

    public function addBadge(BadgeDto $badgeDto): ?string
    {
        return $this->post($badgeDto->serialize());
    }

    public function updateBadge(BadgeDto $badgeDto): bool
    {
        return $this->patch($badgeDto->serialize());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}