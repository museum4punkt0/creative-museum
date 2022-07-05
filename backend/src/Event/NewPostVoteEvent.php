<?php

namespace App\Event;

class NewPostVoteEvent
{
    /**
     * Event Identifier
     */
    public const NAME = 'post.vote.received';

    /**
     * @var int
     */
    protected int $postId;

    /**
     * @var string
     */
    protected string $direction;

    /**
     * @var int
     */
    protected int $voteDifference;

    /**
     * @var bool
     */
    protected bool $switched;

    public function __construct(int $postId, string $direction, int $voteDifference, bool $switched = false)
    {
        $this->postId = $postId;
        $this->direction = $direction;
        $this->voteDifference = $voteDifference;
        $this->switched = $switched;
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return $this->postId;
    }

    /**
     * @return string
     */
    public function getDirection(): string
    {
        return $this->direction;
    }

    /**
     * @return int
     */
    public function getVoteDifference(): int
    {
        return $this->voteDifference;
    }

    /**
     * @return bool
     */
    public function getSwitched(): bool
    {
        return $this->switched;
    }
}