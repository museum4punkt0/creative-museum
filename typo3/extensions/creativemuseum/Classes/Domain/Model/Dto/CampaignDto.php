<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use DateTime;
use Exception;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class CampaignDto extends AbstractEntity
{
    /**
     * @var int|null
     */
    protected int|null $id = null;

    /**
     * @var string
     * @Extbase\Validate("StringLength", options={"minimum": 3, "maximum": 50})
     */
    protected string $title = '';

    /**
     * @var DateTime
     * @Extbase\Validate("NotEmpty")
     * @Extbase\Validate("DateTime")
     */
    protected DateTime|null $start = null;

    /**
     * @var DateTime
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
     * @var bool
     */
    protected $published = false;

    /**
     * @var bool
     */
    protected $closed = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto>
     */
    protected $badges;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\AwardDto>
     */
    protected $awards;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\PartnerDto>
     */
    protected $partners;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto>
     */
    protected $feedbackOptions;

    /**
     * Constructor
     */
    public function initializeObject()
    {
        $this->badges = new ObjectStorage();
        $this->feedbackOptions = new ObjectStorage();
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
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @param bool $published
     * @return CampaignDto
     */
    public function setPublished(bool $published): CampaignDto
    {
        $this->published = $published;
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
     * @return bool
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * @param bool $closed
     * @return CampaignDto
     */
    public function setClosed(bool $closed): CampaignDto
    {
        $this->closed = $closed;
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto>
     */
    public function getBadges(): ObjectStorage
    {
        if (null === $this->badges) {
            return new ObjectStorage();
        }
        return $this->badges;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto> $badges
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
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\AwardDto>
     */
    public function getAwards(): ObjectStorage
    {
        if (null === $this->awards) {
            return new ObjectStorage();
        }
        return $this->awards;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\AwardDto> $awards
     * @return CampaignDto
     */
    public function setAwards(ObjectStorage $awards): CampaignDto
    {
        $this->awards = $awards;
        return $this;
    }

    public function addAward(AwardDto $awardDto): CampaignDto
    {
        if ($this->awards == null) {
            $this->awards = new ObjectStorage();
        }

        $this->awards->attach($awardDto);
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\PartnerDto>
     */
    public function getPartners(): ObjectStorage
    {
        if (null === $this->partners) {
            return new ObjectStorage();
        }
        return $this->partners;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\PartnerDto> $partners
     * @return CampaignDto
     */
    public function setPartners(ObjectStorage $partners): CampaignDto
    {
        $this->partners = $partners;
        return $this;
    }

    public function addPartner(PartnerDto $partnerDto): CampaignDto
    {
        if ($this->partners == null) {
            $this->partners = new ObjectStorage();
        }

        $this->partners->attach($partnerDto);
        return $this;
    }

    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto>
     */
    public function getFeedbackOptions(): ObjectStorage
    {
        if (null === $this->feedbackOptions) {
            return new ObjectStorage();
        }
        return $this->feedbackOptions;
    }

    /**
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto> $feedbackOptions
     * @return CampaignDto
     */
    public function setFeedbackOptions(ObjectStorage $feedbackOptions): CampaignDto
    {
        $this->feedbackOptions = $feedbackOptions;
        return $this;
    }

    /**
     * @param FeedbackOptionDto $feedbackOptionDto
     * @return $this
     */
    public function addFeedbackOption(FeedbackOptionDto $feedbackOptionDto): CampaignDto
    {
        if ($this->feedbackOptions == null) {
            $this->feedbackOptions = new ObjectStorage();
        }

        $this->feedbackOptions->attach($feedbackOptionDto);
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
            'published' => $this->isPublished(),
            'color' => $this->getColor(),
            'closed' => $this->isClosed(),
        ];

        if ($this->getFeedbackOptions()->count() > 0) {
            $campaign['feedbackOptions'] = [];
            /** @var FeedbackOptionDto $option */
            foreach ($this->getFeedbackOptions() as $option) {
                $campaign['feedbackOptions'][] = $option->serialize();
            }
        }

        if ($this->getBadges()->count() > 0) {
            $campaign['badges'] = [];
            /** @var BadgeDto $badge */
            foreach ($this->getBadges() as $badge) {
                $campaign['badges'][] = $badge->serialize();
            }
        }

        if ($this->getAwards()->count() > 0) {
            $campaign['awards'] = [];
            /** @var AwardDto $award */
            foreach ($this->getAwards() as $award) {
                $campaign['awards'][] = $award->serialize();
            }
        }

        if ($this->getPartners()->count() > 0) {
            $campaign['partners'] = [];
            /** @var PartnerDto $partner */
            foreach ($this->getPartners() as $partner) {
                $campaign['partners'][] = $partner->serialize();
            }
        }

        return $campaign;
    }
}