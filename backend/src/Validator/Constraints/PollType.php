<?php

namespace App\Validator\Constraints;

use App\Validator\PollTypeValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PollType extends Constraint
{
    public $tooMuchPollOptions = 'Too much poll options (max. 5)';

    public $tooFewPollOptions = 'Too few poll options (min. 2)';

    public $emptyQuestion = 'Post of type poll needs a question';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return PollTypeValidator::class;
    }
}