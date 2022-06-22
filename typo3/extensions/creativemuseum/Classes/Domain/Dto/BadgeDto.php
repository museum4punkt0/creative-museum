<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Dto;

use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class BadgeDto extends AbstractDomainObject
{
    /**
     * @var string|null
     */
    protected $id = null;

    /**
     * @var int
     */
    protected $threshold = 0;

    /**
     * @var string
     */
    protected $badgeType;

    /**
     * @var string
     */
    protected $postType;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var CampaignDto|null
     */
    protected $campaign = null;

    /**
     * @var int
     */
    protected $price;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return BadgeDto
     */
    public function setId(?string $id): BadgeDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getThreshold(): int
    {
        return $this->threshold;
    }

    /**
     * @param int $threshold
     * @return BadgeDto
     */
    public function setThreshold(int $threshold): BadgeDto
    {
        $this->threshold = $threshold;
        return $this;
    }

    /**
     * @return string
     */
    public function getBadgeType(): string
    {
        return $this->badgeType;
    }

    /**
     * @param string $badgeType
     * @return BadgeDto
     */
    public function setBadgeType(string $badgeType): BadgeDto
    {
        $this->badgeType = $badgeType;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostType(): string
    {
        return $this->postType;
    }

    /**
     * @param string $postType
     * @return BadgeDto
     */
    public function setPostType(string $postType): BadgeDto
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return BadgeDto
     */
    public function setTitle(string $title): BadgeDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return BadgeDto
     */
    public function setDescription(string $description): BadgeDto
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return CampaignDto
     */
    public function getCampaign(): ?CampaignDto
    {
        return $this->campaign;
    }

    /**
     * @param CampaignDto $campaign
     * @return BadgeDto
     */
    public function setCampaign(CampaignDto $campaign): BadgeDto
    {
        $this->campaign = $campaign;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return BadgeDto
     */
    public function setPrice(int $price): BadgeDto
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $data = [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ];

        if (null !== $this->getId() && ! empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (null !== $this->getCampaign()) {
            $data['campaign'] = '/' . CampaignService::ENDPOINT . '/' . $this->getCampaign()->getId();
        }

        return $data;
    }
}