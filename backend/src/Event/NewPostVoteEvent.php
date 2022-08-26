<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class NewPostVoteEvent extends Event
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
     * @var string|null
     */
    protected ?string $oldDirection;

    public function __construct(int $postId, string $direction, string $oldDirection = null)
    {
        $this->postId = $postId;
        $this->direction = $direction;
        $this->oldDirection = $oldDirection;
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
     * @return string|null
     */
    public function getOldDirection(): ?string
    {
        return $this->oldDirection;
    }
}