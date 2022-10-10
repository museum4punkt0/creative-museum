<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class BadgeDto extends AbstractEntity
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
     * @var string|null
     */
    protected ?string $badgeType = null;

    /**
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * @var string|null
     */
    protected ?string $description = null;

    /**
     * @var CampaignDto|null
     */
    protected $campaign = null;

    /**
     * @var MediaObjectDto|null
     */
    protected $picture = null;

    /**
     * @var string
     */
    protected $pictureIRI = '';

    /**
     * @var string
     */
    protected string $shortDescription = '';

    /**
     * @var string
     */
    protected string $link = '';

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
     * @return string|null
     */
    public function getBadgeType(): ?string
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
     * @return string|null
     */
    public function getTitle(): ?string
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
     * @return string|null
     */
    public function getDescription(): ?string
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
     * @return MediaObjectDto|null
     */
    public function getPicture(): ?MediaObjectDto
    {
        return $this->picture;
    }

    /**
     * @param MediaObjectDto|null $picture
     * @return BadgeDto
     */
    public function setPicture(?MediaObjectDto $picture): BadgeDto
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * @return string
     */
    public function getPictureIRI(): string
    {
        return $this->pictureIRI;
    }

    /**
     * @param string $pictureIRI
     * @return BadgeDto
     */
    public function setPictureIRI(string $pictureIRI): BadgeDto
    {
        $this->pictureIRI = $pictureIRI;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    /**
     * @param string $shortDescription
     * @return BadgeDto
     */
    public function setShortDescription(string $shortDescription): BadgeDto
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     * @return BadgeDto
     */
    public function setLink(string $link): BadgeDto
    {
        $this->link = $link;
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
            'type' => $this->getBadgeType(),
            'threshold' => $this->getThreshold(),
            'shortDescription' => $this->getShortDescription(),
            'link' => $this->getLink()
        ];

        if (null !== $this->getId() && ! empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (! empty($this->pictureIRI)) {
            $data['picture'] = $this->pictureIRI;
        }

        if (null !== $this->getCampaign()) {
            $data['campaign'] = '/' . CampaignService::ENDPOINT . '/' . $this->getCampaign()->getId();
        }

        return $data;
    }
}