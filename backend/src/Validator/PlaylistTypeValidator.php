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
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class PlaylistTypeValidator extends ConstraintValidator
{
    /**
     * @param $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Post) {
            return;
        }

        /**
         * @var Post $value
         */
        if (PostType::PLAYLIST === $value->getPostType() && !$value->getLinkedPlaylist()) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
