<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation as Extbase;

class FeedbackOptionDto extends AbstractEntity
{
    /** @var string|null */
    protected $id = null;

    /**
     * @var string
     * @Extbase\Validate("StringLength", options={"minimum": 2, "maximum": 50})
     */
    protected $text;

    /**
     * @var CampaignDto|null
     */
    protected $campaign = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return FeedbackOptionDto
     */
    public function setId(?string $id): FeedbackOptionDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return FeedbackOptionDto
     */
    public function setText(string $text): FeedbackOptionDto
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return CampaignDto|null
     */
    public function getCampaign(): ?CampaignDto
    {
        return $this->campaign;
    }

    /**
     * @param CampaignDto|null $campaign
     * @return FeedbackOptionDto
     */
    public function setCampaign(?CampaignDto $campaign): FeedbackOptionDto
    {
        $this->campaign = $campaign;
        return $this;
    }

    public function serialize()
    {
        $data = ['text' => $this->getText()];

        if (! empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (null !== $this->getCampaign()) {
            $data['campaign'] = '/' . CampaignService::ENDPOINT . '/' . $this->getCampaign()->getId();
        }

        return $data;
    }
}