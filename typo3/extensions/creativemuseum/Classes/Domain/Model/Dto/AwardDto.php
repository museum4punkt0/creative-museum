<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use JWIED\Creativemuseum\Service\CampaignService;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;

class AwardDto extends AbstractDomainObject
{
    /**
     * @var string|null
     */
    protected $id = null;

    /**
     * @var int
     */
    protected $price = 0;

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
     * @return AwardDto
     */
    public function setId(?string $id): AwardDto
    {
        $this->id = $id;
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
     * @return AwardDto
     */
    public function setPrice(int $price): AwardDto
    {
        $this->price = $price;
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
     * @return AwardDto
     */
    public function setTitle(string $title): AwardDto
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
     * @return AwardDto
     */
    public function setDescription(string $description): AwardDto
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
     * @return AwardDto
     */
    public function setCampaign(CampaignDto $campaign): AwardDto
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
     * @return AwardDto
     */
    public function setPicture(?MediaObjectDto $picture): AwardDto
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
     * @return AwardDto
     */
    public function setPictureIRI(string $pictureIRI): AwardDto
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
     * @return AwardDto
     */
    public function setLink(string $link): AwardDto
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
            'price' => $this->getPrice()
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