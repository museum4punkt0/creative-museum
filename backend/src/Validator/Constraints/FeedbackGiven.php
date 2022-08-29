<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator\Constraints;

use App\Validator\FeedbackGivenValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FeedbackGiven extends Constraint
{
    public string $feedbackAlreadyGivenMessage = 'The user has already provided feedback on this post';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return FeedbackGivenValidator::class;
    }
}
