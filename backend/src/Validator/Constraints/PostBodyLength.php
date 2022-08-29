<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator\Constraints;

use App\Validator\PostBodyLengthValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PostBodyLength extends Constraint
{
    public $tooPostBodyMessage = 'The value of field body is too long for post type %type% (Max. %max%).';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return PostBodyLengthValidator::class;
    }
}
