<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Dto;

use Exception;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class CampaignDto extends AbstractEntity
{
    /**
     * @var int|null
     */
    protected $id = null;

    /**
     * @var string
     * @Extbase\Validate("StringLength", options={"minimum": 3, "maximum": 50})
     */
    protected $title = '';

    /**
     * @var \DateTime
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("DateTime")
     */
    protected $start = null;

    /**
     * @var \DateTime
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("DateTime")
     */
    protected $stop = null;

    /**
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $shortDesc = '';

    /**
     * @var string
     * @Extbase\Validate("NotEmpty")
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $color = '';

    /**
     * @var bool
     */
    protected $active = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Dto\BadgeDto>
     */
    protected $badges;

    /**
     * Constructor
     */
    public function initializeObject()
    {
        $this->badges = new ObjectStorage();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;
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
     * @return CampaignDto
     */
    public function setTitle(string $title): CampaignDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     * @return CampaignDto
     */
    public function setStart(\DateTime $start = null): CampaignDto
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStop(): ?\DateTime
    {
        return $this->stop;
    }

    /**
     * @param \DateTime $stop
     * @return CampaignDto
     */
    public function setStop(\DateTime $stop = null): CampaignDto
    {
        $this->stop = $stop;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortDesc(): string
    {
        return $this->shortDesc;
    }

    /**
     * @param string $shortDesc
     * @return CampaignDto
     */
    public function setShortDesc(string $shortDesc): CampaignDto
    {
        $this->shortDesc = $shortDesc;
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
     * @return CampaignDto
     */
    public function setDescription(string $description): CampaignDto
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return CampaignDto
     */
    public function setColor(string $color): CampaignDto
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return CampaignDto
     */
    public function setActive(bool $active): CampaignDto
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Dto\BadgeDto>
     */
    public function getBadges(): ?ObjectStorage
    {
        return $this->badges;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Dto\BadgeDto> $badges
     * @return CampaignDto
     */
    public function setBadges(ObjectStorage $badges): CampaignDto
    {
        $this->badges = $badges;
        return $this;
    }

    public function addBadge(BadgeDto $badgeDto): CampaignDto
    {
        if ($this->badges == null) {
            $this->badges = new ObjectStorage();
        }

        $this->badges->attach($badgeDto);
        return $this;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $campaign = [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'start' => $this->getStart()->format('c'),
            'stop' => $this->getStop()->format('c'),
            'shortDescription' => $this->getShortDesc(),
            'description' => $this->getDescription(),
            'active' => $this->isActive(),
            'color' => $this->getColor()
        ];

        return $campaign;
    }
}