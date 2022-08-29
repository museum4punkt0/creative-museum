<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\Campaign;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class FeedbackOptionCountValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
//        dd($value);
        if (!$value instanceof Campaign) {
            return;
        }

        if ($value->getFeedbackOptions()->count() > 5) {
            $this->context->buildViolation($constraint->tooMuchFeedbackOptions)->addViolation();
        } elseif ($value->getFeedbackOptions()->count() < 2) {
            $this->context->buildViolation($constraint->tooFewFeedbackOptions)->addViolation();
        }
    }
}
