<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

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
