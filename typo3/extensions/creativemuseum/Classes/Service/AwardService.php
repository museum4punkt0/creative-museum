<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\AwardDto;

class AwardService extends CmApiService
{
    const ENDPOINT = 'v1/awards';

    public function addAward(AwardDto $awardDto): ?string
    {
        return $this->post($awardDto->serialize());
    }

    public function updateAward(AwardDto $awardDto): bool
    {
        return $this->patch($awardDto->serialize());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}