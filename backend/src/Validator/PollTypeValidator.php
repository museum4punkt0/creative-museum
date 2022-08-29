<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\Post;
use App\Enum\PostType;
use App\Validator\Constraints\PollType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class PollTypeValidator extends ConstraintValidator
{
    /**
     * @param $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Post) {
            return;
        }

        if (!(PostType::POLL === $value->getPostType())) {
            return;
        }

        /**
         * @var Post     $value
         * @var PollType $constraint
         */
        if ($value->getPollOptions()->count() > 5) {
            $this->context->buildViolation($constraint->tooMuchPollOptions)->addViolation();
        } elseif ($value->getPollOptions()->count() < 2) {
            $this->context->buildViolation($constraint->tooFewPollOptions)->addViolation();
        }

        if (empty($value->getQuestion())) {
            $this->context->buildViolation($constraint->emptyQuestion)->addViolation();
        }
    }
}
