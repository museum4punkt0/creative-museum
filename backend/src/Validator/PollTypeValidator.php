<?php

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
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Post) {
            return;
        }

        if (!$value->getPostType() === PostType::POLL) {
            return;
        }

        /**
         * @var Post $value
         * @var PollType $constraint
         */
        if ($value->getPollOptions()->count() > 5) {
            $this->context->buildViolation($constraint->tooMuchPollOptions)->addViolation();
        }

        if ($value->getPollOptions()->count() < 2) {
            $this->context->buildViolation($constraint->tooFewPollOptions)->addViolation();
        }

        if (empty($value->getQuestion())) {
            $this->context->buildViolation($constraint->emptyQuestion)->addViolation();
        }
    }
}