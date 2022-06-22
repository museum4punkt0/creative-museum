<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Dto\BadgeDto;

class BadgeService extends CmApiService
{
    const ENDPOINT = 'v1/badges';

    /**
     * @param BadgeDto $badgeDto
     * @return string|null
     */
    public function addBadge(BadgeDto $badgeDto): ?string
    {
        return $this->post($badgeDto->serialize());
    }

    public function updateBadge(BadgeDto $badgeDto)
    {
        return $this->patch($badgeDto->serialize());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}