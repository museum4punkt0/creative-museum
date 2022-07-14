<?php

namespace App\Validator\Constraints;

use App\Validator\PollOptionChoiceValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PollOptionChoiced extends Constraint
{
    public string $alreadyVotedForPollMessage = 'User already voted for poll';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return PollOptionChoiceValidator::class;
    }
}