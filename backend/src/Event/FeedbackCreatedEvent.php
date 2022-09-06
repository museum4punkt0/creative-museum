<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Event;

use App\Entity\PostFeedback;
use Symfony\Contracts\EventDispatcher\Event;

final class FeedbackCreatedEvent extends Event
{
    public const NAME = 'feedback.created';

    private PostFeedback $feedbackItem;

    public function __construct(PostFeedback $feedbackItem)
    {
        $this->feedbackItem = $feedbackItem;
    }

    public function getPostFeedback(): PostFeedback
    {
        return $this->feedbackItem;
    }
}
