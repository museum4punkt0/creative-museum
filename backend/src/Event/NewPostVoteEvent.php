<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class NewPostVoteEvent extends Event
{
    /**
     * Event Identifier.
     */
    public const NAME = 'post.vote.received';

    protected int $postId;

    protected string $direction;

    protected ?string $oldDirection;

    public function __construct(int $postId, string $direction, string $oldDirection = null)
    {
        $this->postId = $postId;
        $this->direction = $direction;
        $this->oldDirection = $oldDirection;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function getOldDirection(): ?string
    {
        return $this->oldDirection;
    }
}
