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
class PostBodyLengthValidator extends ConstraintValidator
{
    private int $textPostBodyLength;

    private int $imagePostBodyLength;

    private int $videoPostBodyLength;

    private Constraint $constraint;

    public function __construct(
        int $textPostBodyLength,
        int $imagePostBodyLength,
        int $videoPostBodyLength
    ) {
        $this->textPostBodyLength = $textPostBodyLength;
        $this->imagePostBodyLength = $imagePostBodyLength;
        $this->videoPostBodyLength = $videoPostBodyLength;
    }

    /**
     * @param $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Post) {
            return;
        }
        $this->constraint = $constraint;

        switch ($value->getPostType()) {
            case PostType::TEXT:
                $this->validateBodyLength($value->getBody(), PostType::TEXT->value, $this->textPostBodyLength, 1);
                break;
            case PostType::IMAGE:
                $this->validateBodyLength($value->getBody(), PostType::IMAGE->value, $this->imagePostBodyLength);
                break;
            case PostType::VIDEO:
                $this->validateBodyLength($value->getBody(), PostType::VIDEO->value, $this->videoPostBodyLength);
                break;
        }
    }

    private function validateBodyLength(string $bodyValue, string $postType, int $maxLength, int $minLength = 0): void
    {
        if (strlen($bodyValue) > $maxLength) {
            $this->context->buildViolation($this->constraint->tooLongPostBodyMessage)
                ->setParameter('%type%', $postType)
                ->setParameter('%max%', $maxLength)
                ->setCode(1657590177)
                ->addViolation();
        } elseif (strlen($bodyValue) < $minLength) {
            $this->context->buildViolation($this->constraint->tooShortBodyMessage)
                ->setParameter('%min%', $minLength)
                ->setCode(1662115657)
                ->addViolation();
        }
    }
}
