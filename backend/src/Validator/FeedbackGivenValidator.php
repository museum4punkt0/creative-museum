<?php

namespace App\Validator;

use App\Entity\PostFeedback;
use App\Service\PostFeedbackService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class FeedbackGivenValidator extends ConstraintValidator
{
    /**
     * @var PostFeedbackService
     */
    private PostFeedbackService $feedbackService;

    public function __construct(PostFeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$value instanceof PostFeedback) {
            return;
        }

        $givenFeedback = $this->feedbackService->getGivenFeedbackByPostAndUser($value->getPost(), $value->getUser()->getId());

        if (empty($givenFeedback)) {
            return;
        }

        $this->context->buildViolation($constraint->feedbackAlreadyGivenMessage)->addViolation();
    }
}