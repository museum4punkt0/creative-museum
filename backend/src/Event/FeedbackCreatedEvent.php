<?php

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