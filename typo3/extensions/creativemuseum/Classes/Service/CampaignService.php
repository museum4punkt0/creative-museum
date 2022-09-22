<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto;
use JWIED\Creativemuseum\Domain\Model\Dto\CampaignDto;
use JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto;
use JWIED\Creativemuseum\Domain\Model\Dto\MediaObjectDto;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class CampaignService extends CmApiService
{
    const ENDPOINT = 'v1/campaigns';

    const BADGES_ENDPOINT = 'v1/badges';

    private BadgeService $badgeService;

    private FeedbackOptionService $feedbackOptionService;

    public function __construct(
        ExtensionConfiguration $config,
        BadgeService $badgeService,
        FeedbackOptionService $feedbackOptionService
    ) {
        parent::__construct($config);
        $this->badgeService = $badgeService;
        $this->feedbackOptionService = $feedbackOptionService;
    }

    public function getCampaigns(): ?array
    {
        return $this->get();
    }

    public function getCampaign(int $campaignId): ?array
    {
        return $this->getSingle($campaignId);
    }

    public function createCampaign(CampaignDto $campaignDto)
    {
        $this->post(data: $campaignDto->serialize(true));
    }

    public function updateCampaign(CampaignDto $campaignDto)
    {
        $campaignArray = $campaignDto->serialize();
        $badgeIds = [];

        if (null !== $campaignDto->getFeedbackOptions() && $campaignDto->getFeedbackOptions()->count() > 0) {
            $this->processFeedbackOptionsToUpdate($campaignArray, $campaignDto);
        }

        if (null !== $campaignDto->getBadges() && $campaignDto->getBadges()->count()) {
            /** @var BadgeDto $badgeDto */
            foreach ($campaignDto->getBadges() as $badgeDto) {
                if ($badgeDto->getId() === '') {
                    $badgeDto->setCampaign($campaignDto);
                    $badgeId = $this->badgeService->addBadge($badgeDto);
                    if (null !== $badgeId) {
                        $badgeIds[] = $badgeId;
                    }
                    continue;
                }
                if ($this->badgeService->updateBadge($badgeDto)) {
                    $badgeIds[] = '/' . BadgeService::ENDPOINT . '/' . $badgeDto->getId();
                }

            }
        }
        $campaignArray['badges'] = $badgeIds;

        $this->patch($campaignArray);
    }

    private function processFeedbackOptionsToUpdate(array &$campaignArray, CampaignDto $campaignDto)
    {
        $feedbackOptionIds = [];

        /** @var FeedbackOptionDto $option */
        foreach ($campaignDto->getFeedbackOptions() as $option) {
            if ($option->getId() === '') {
                $option->setCampaign($campaignDto);
                $optionId = $this->feedbackOptionService->addFeedbackOption($option);
                if (null !== $optionId) {
                    $feedbackOptionIds[] = $optionId;
                }
                continue;
            }
            if ($this->feedbackOptionService->updateFeedbackOption($option)) {
                $feedbackOptionIds[] = '/' . FeedbackOptionService::ENDPOINT . '/' . $option->getId();
            }
        }

        $campaignArray['feedbackOptions'] = $feedbackOptionIds;
    }

    public function removeCampaign(CampaignDto $campaignDto)
    {
        $this->delete($campaignDto->getId());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }

    public function convert(array $campaign): CampaignDto {
        $dto = new CampaignDto();
        $dto->setId((int) $campaign['id']);
        $dto->setTitle($campaign['title']);
        $dto->setStart(new \DateTime($campaign['start']));
        $dto->setStop(new \DateTime($campaign['stop']));
        $dto->setShortDesc($campaign['shortDescription']);
        $dto->setDescription($campaign['description']);
        $dto->setActive($campaign['active']);
        $dto->setColor($campaign['color'] ?? '');
        $dto->setClosed($campaign['closed']);

        if (isset($campaign['feedbackOptions']) && count($campaign['feedbackOptions']) > 0) {
            $this->addFeedbackOptions($dto, $campaign['feedbackOptions']);
        }

        if (isset($campaign['badges']) && count($campaign['badges']) > 0) {
            $this->addBadges($dto, $campaign['badges']);
        }

        return $dto;
    }

    private function addFeedbackOptions(CampaignDto $dto, array $feedbackOptions): void
    {
        foreach ($feedbackOptions as $feedbackOption) {
            $feedbackOptionDto = new FeedbackOptionDto();
            $feedbackOptionDto->setId((string) $feedbackOption['id']);
            $feedbackOptionDto->setText($feedbackOption['text']);
            $dto->addFeedbackOption($feedbackOptionDto);
        }
    }

    private function addBadges(CampaignDto $dto, array $badges): void
    {
        foreach ($badges as $badge) {
            $badgeDto = new BadgeDto();
            $badgeDto->setId((string) $badge['id']);
            $badgeDto->setBadgeType($badge['type']);
            $badgeDto->setThreshold($badge['threshold']);
            $badgeDto->setTitle($badge['title']);
            $badgeDto->setDescription($badge['description']);
            $badgeDto->setCampaign($dto);

            if (isset($badge['picture'])) {
                $picture = new MediaObjectDto();
                $picture->setId($badge['picture']['id']);
                $picture->setContentUrl($badge['picture']['contentUrl']);
                $picture->setDescription($badge['picture']['description'] ?? '');
                $badgeDto->setPicture($picture);
            }

            $dto->addBadge($badgeDto);
        }
    }
}