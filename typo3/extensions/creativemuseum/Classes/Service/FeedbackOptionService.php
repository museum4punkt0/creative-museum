<?php
declare(strict_types=1);

namespace JWIED\Creativemuseum\Service;

use JWIED\Creativemuseum\Domain\Model\Dto\BadgeDto;
use JWIED\Creativemuseum\Domain\Model\Dto\FeedbackOptionDto;

class FeedbackOptionService extends CmApiService
{
    const ENDPOINT = 'v1/campaign_feedback_options';

    public function addFeedbackOption(FeedbackOptionDto $feedbackOptionDto): ?string
    {
        return $this->post($feedbackOptionDto->serialize());
    }

    public function updateFeedbackOption(FeedbackOptionDto $feedbackOptionDto): bool
    {
        return $this->patch($feedbackOptionDto->serialize());
    }

    public function getEndpoint(): string
    {
        return self::ENDPOINT;
    }
}