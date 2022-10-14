<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PollOptionDto extends AbstractEntity
{
    /**
     * @var string|null
     */
    protected $id = null;

    /**
     * @var int
     */
    protected $votes = 0;

    /**
     * @var string|null
     */
    protected ?string $title = null;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return PollOptionDto
     */
    public function setId(?string $id): PollOptionDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @param int $votes
     * @return PollOptionDto
     */
    public function setVotes(int $votes): PollOptionDto
    {
        $this->votes = $votes;
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
     * @return PollOptionDto
     */
    public function setTitle(string $title): PollOptionDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return array
     */
    public function serialize()
    {
        $data = [
            'title' => $this->getTitle(),
            'votes' => $this->getVotes(),
        ];

        if (null !== $this->getId() && ! empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        return $data;
    }
}