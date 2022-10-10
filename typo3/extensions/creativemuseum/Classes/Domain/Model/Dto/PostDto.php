<?php

declare(strict_types=1);

namespace JWIED\Creativemuseum\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class PostDto extends AbstractEntity
{
    protected string $id;

    protected string $author;

    protected string $campaignTitle;

    protected \DateTime $created;

    protected string $type;

    protected string $title;

    protected string $body;

    protected int $upvotes;

    protected int $downvotes;

    protected int $commentCount;

    protected bool $reported;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return PostDto
     */
    public function setId(string $id): PostDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     * @return PostDto
     */
    public function setAuthor(string $author): PostDto
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return string
     */
    public function getCampaignTitle(): string
    {
        return $this->campaignTitle;
    }

    /**
     * @param string $campaignTitle
     * @return PostDto
     */
    public function setCampaignTitle(string $campaignTitle): PostDto
    {
        $this->campaignTitle = $campaignTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return PostDto
     */
    public function setType(string $type): PostDto
    {
        $this->type = $type;
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
     * @return PostDto
     */
    public function setTitle(string $title): PostDto
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     * @return PostDto
     */
    public function setBody(string $body): PostDto
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpvotes(): int
    {
        return $this->upvotes;
    }

    /**
     * @param int $upvotes
     * @return PostDto
     */
    public function setUpvotes(int $upvotes): PostDto
    {
        $this->upvotes = $upvotes;
        return $this;
    }

    /**
     * @return int
     */
    public function getDownvotes(): int
    {
        return $this->downvotes;
    }

    /**
     * @param int $downvotes
     * @return PostDto
     */
    public function setDownvotes(int $downvotes): PostDto
    {
        $this->downvotes = $downvotes;
        return $this;
    }

    /**
     * @return int
     */
    public function getCommentCount(): int
    {
        return $this->commentCount;
    }

    /**
     * @param int $commentCount
     * @return PostDto
     */
    public function setCommentCount(int $commentCount): PostDto
    {
        $this->commentCount = $commentCount;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReported(): bool
    {
        return $this->reported;
    }

    /**
     * @param bool $reported
     * @return PostDto
     */
    public function setReported(bool $reported): PostDto
    {
        $this->reported = $reported;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return PostDto
     */
    public function setCreated(\DateTime $created): PostDto
    {
        $this->created = $created;
        return $this;
    }



    /**
     * @return array
     */
    public function serialize()
    {
        $data = [

        ];

        return $data;
    }
}