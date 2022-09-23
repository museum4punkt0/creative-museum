<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\AwardDto;
use JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto;
use JWIED\Creativemuseum\Domain\Model\Dto\CampaignDto;
use JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto;
use JWIED\Creativemuseum\Domain\Model\Dto\MediaObjectDto;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;

class CampaignService extends CmApiService
{
    const ENDPOINT = 'v1/campaigns';

    private BadgeService $badgeService;

    private AwardService $awardService;

    private FeedbackOptionService $feedbackOptionService;

    public function __construct(
        ExtensionConfiguration $config,
        BadgeService $badgeService,
        AwardService $awardService,
        FeedbackOptionService $feedbackOptionService
    ) {
        parent::__construct($config);
        $this->badgeService = $badgeService;
        $this->awardService = $awardService;
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
        $awardIds = [];

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

        if (null !== $campaignDto->getAwards() && $campaignDto->getAwards()->count()) {
            /** @var AwardDto $awardDto */
            foreach ($campaignDto->getAwards() as $awardDto) {
                if ($awardDto->getId() === '') {
                    $awardDto->setCampaign($campaignDto);
                    $awardId = $this->awardService->addAward($awardDto);
                    if (null !== $awardId) {
                        $awardIds[] = $awardId;
                    }
                    continue;
                }
                if ($this->awardService->updateAward($awardDto)) {
                    $awardIds[] = '/' . AwardService::ENDPOINT . '/' . $awardDto->getId();
                }

            }
        }
        $campaignArray['awards'] = $awardIds;

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

        if (isset($campaign['awards']) && count($campaign['awards']) > 0) {
            $this->addAwards($dto, $campaign['awards']);
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

    private function addAwards(CampaignDto $dto, array $awards): void
    {
        foreach ($awards as $award) {
            $awardDto = new AwardDto();
            $awardDto->setId((string) $award['id']);
            $awardDto->setTitle($award['title']);
            $awardDto->setDescription($award['description']);
            $awardDto->setPrice((int) $award['price']);
            $awardDto->setCampaign($dto);

            if (isset($award['picture'])) {
                $picture = new MediaObjectDto();
                $picture->setId($award['picture']['id']);
                $picture->setContentUrl($award['picture']['contentUrl']);
                $picture->setDescription($award['picture']['description'] ?? '');
                $awardDto->setPicture($picture);
            }

            $dto->addAward($awardDto);
        }
    }
}