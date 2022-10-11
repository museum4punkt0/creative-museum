<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PartnerDto extends AbstractEntity
{
    /**
     * @var string|null
     */
    protected $id = null;

    /**
     * @var string|null
     */
    protected ?string $title = null;

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
     * @return PartnerDto
     */
    public function setId(?string $id): PartnerDto
    {
        $this->id = $id;
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
     * @return PartnerDto
     */
    public function setTitle(string $title): PartnerDto
    {
        $this->title = $title;
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
     * @param CampaignDto $campaign
     * @return PartnerDto
     */
    public function setCampaign(CampaignDto $campaign): PartnerDto
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
     * @return PartnerDto
     */
    public function setPicture(?MediaObjectDto $picture): PartnerDto
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
     * @return PartnerDto
     */
    public function setPictureIRI(string $pictureIRI): PartnerDto
    {
        $this->pictureIRI = $pictureIRI;
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
     * @return PartnerDto
     */
    public function setLink(string $link): PartnerDto
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
            'url' => $this->getLink()
        ];

        if (null !== $this->getId() && ! empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (! empty($this->pictureIRI)) {
            $data['logo'] = $this->pictureIRI;
        }

        if (null !== $this->getCampaign()) {
            $data['campaign'] = '/' . CampaignService::ENDPOINT . '/' . $this->getCampaign()->getId();
        }

        return $data;
    }
}