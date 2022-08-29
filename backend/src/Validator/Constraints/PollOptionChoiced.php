<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
