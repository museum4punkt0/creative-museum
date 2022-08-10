<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\PostFeedbackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GetPostFeedbackResultsController extends AbstractController
{
    public function __construct(PostFeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function __invoke(int $postId)
    {
        $results = $this->feedbackService->getFeedbackResultsForPost($postId);
        return $results;
    }
}