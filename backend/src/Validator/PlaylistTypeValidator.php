<?php

namespace App\Validator;

use App\Entity\Post;
use App\Enum\PostType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class PlaylistTypeValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Post){
            return;
        }

        /**
         * @var Post $value
         */
        if ($value->getPostType() === PostType::PLAYLIST && $value->getPlaylist()->isEmpty()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}