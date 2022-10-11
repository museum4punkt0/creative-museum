<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\PartnerDto;

class PartnerService extends CmApiService
{
    const ENDPOINT = 'v1/partners';

    public function addPartner(PartnerDto $partnerDto): ?string
    {
        return $this->post($partnerDto->serialize());
    }

    public function updatePartner(PartnerDto $partnerDto): bool
    {
        return $this->patch($partnerDto->serialize());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}