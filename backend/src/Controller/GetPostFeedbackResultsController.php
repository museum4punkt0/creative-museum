<?php

declare(strict_types=1);

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
