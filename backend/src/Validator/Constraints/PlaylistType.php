<?php

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