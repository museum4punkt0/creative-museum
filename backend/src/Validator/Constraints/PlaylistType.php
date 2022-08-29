<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator\Constraints;

use App\Validator\PlaylistTypeValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PlaylistType extends Constraint
{
    public $message = 'Post of type playlist needs an assigned playlist';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return PlaylistTypeValidator::class;
    }
}
