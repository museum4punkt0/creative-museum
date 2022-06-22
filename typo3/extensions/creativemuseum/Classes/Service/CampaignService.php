<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Dto\BadgeDto;
use JWIED\Creativemuseum\Domain\Dto\CampaignDto;

class CampaignService extends CmApiService
{
    const ENDPOINT = 'v1/campaigns';

    const BADGES_ENDPOINT = 'v1/badges';

    private BadgeService $badgeService;

    /**
     * @param BadgeService $badgeService
     *
     * @return void
     */
    public function injectBadgeService(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    public function getCampaigns()
    {
        return $this->get();
    }

    public function getCampaign(int $campaignId)
    {
        return $this->getSingle($campaignId);
    }

    public function createCampaign(CampaignDto $campaignDto)
    {
        $this->post($campaignDto->serialize(true));
    }

    public function updateCampaign(CampaignDto $campaignDto)
    {
        $campaignArray = $campaignDto->serialize();
        $badgeIds = [];

        if (null !== $campaignDto->getBadges() && $campaignDto->getBadges()->count()) {
            /** @var BadgeDto $badgeDto */
            foreach ($campaignDto->getBadges() as $badgeDto) {
                if ($badgeDto->getId() === '') {
                    $badgeDto->setCampaign($campaignDto);
                    $badgeId = $this->badgeService->addBadge($badgeDto);
                    if (null !== $badgeId) {
                        $badgeIds[] = $badgeId;
                    }
                } else {
                    if ($this->badgeService->updateBadge($badgeDto)) {
                        $badgeIds[] = '/' . BadgeService::ENDPOINT . '/' . $badgeDto->getId();
                    }
                }
            }
        }
        $campaignArray['badges'] = $badgeIds;

        $this->patch($campaignArray);
    }

    public function removeCampaign(CampaignDto $campaignDto)
    {
        $this->delete($campaignDto->getId());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function convert(array $campaign) {
        $dto = new CampaignDto();
        $dto->setId((int) $campaign['id']);
        $dto->setTitle($campaign['title']);
        $dto->setStart(new \DateTime($campaign['start']));
        $dto->setStop(new \DateTime($campaign['stop']));
        $dto->setShortDesc($campaign['shortDescription']);
        $dto->setDescription($campaign['description']);
        $dto->setActive($campaign['active']);

        if (isset($campaign['badges']) && count($campaign['badges']) > 0) {
            foreach ($campaign['badges'] as $badge) {
                $badgeDto = new BadgeDto();
                $badgeDto->setId((string) $badge['id']);
                $badgeDto->setBadgeType($badge['type']);
                $badgeDto->setThreshold($badge['threshold']);
                $badgeDto->setTitle($badge['title']);
                $badgeDto->setDescription($badge['description']);
                $badgeDto->setCampaign($dto);

                $dto->addBadge($badgeDto);
            }
        }

        return $dto;
    }
}