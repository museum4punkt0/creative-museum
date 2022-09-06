<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\PollOptionChoice;
use App\Service\PollOptionChoiceService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class PollOptionChoiceValidator extends ConstraintValidator
{
    private PollOptionChoiceService $pollOptionChoiceService;

    public function __construct(
        PollOptionChoiceService $pollOptionChoiceService,
    ) {
        $this->pollOptionChoiceService = $pollOptionChoiceService;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof PollOptionChoice) {
            return;
        }

        $choice = $this->pollOptionChoiceService->getChoiceByPostAndUser($value->getPollOption()->getPost()->getId(), $value->getUser()->getId());

        if (empty($choice)) {
            return;
        }

        $this->context->buildViolation($constraint->alreadyVotedForPollMessage)->addViolation();
    }
}
