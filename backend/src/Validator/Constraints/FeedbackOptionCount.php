<?php

namespace App\Validator\Constraints;

use App\Validator\FeedbackOptionCountValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FeedbackOptionCount extends Constraint
{
    public string $tooFewFeedbackOptions = 'Too few feedback options provided (min. 2)';

    public string $tooMuchFeedbackOptions = 'Too much feedback options provided (max. 5)';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return FeedbackOptionCountValidator::class;
    }
}