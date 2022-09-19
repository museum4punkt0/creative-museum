<?php

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class MediaObjectDto extends AbstractEntity
{
    /**
     * @var int|null
     */
    protected $id = null;

    /**
     * @var string
     */
    protected $contentUrl = '';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return MediaObjectDto
     */
    public function setId(int $id): MediaObjectDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getContentUrl(): string
    {
        return $this->contentUrl;
    }

    /**
     * @param string $contentUrl
     * @return MediaObjectDto
     */
    public function setContentUrl(string $contentUrl): MediaObjectDto
    {
        $this->contentUrl = $contentUrl;
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
     * @return MediaObjectDto
     */
    public function setDescription(string $description): MediaObjectDto
    {
        $this->description = $description;
        return $this;
    }
}