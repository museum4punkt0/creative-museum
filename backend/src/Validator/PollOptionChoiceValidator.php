<?php

namespace App\Validator;

use App\Service\PollOptionChoiceService;
use App\Entity\PollOptionChoice;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class PollOptionChoiceValidator extends ConstraintValidator
{
    /**
     * @var PollOptionChoiceService
     */
    private PollOptionChoiceService $pollOptionChoiceService;

    public function __construct
    (
        PollOptionChoiceService $pollOptionChoiceService,
    )
    {
        $this->pollOptionChoiceService = $pollOptionChoiceService;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof PollOptionChoice) {
            return;
        }

        $choiced = $this->pollOptionChoiceService->getChoicedByPostAndUser($value->getPollOption()->getPost()->getId(), $value->getUser()->getId());

        if (empty($choiced)) {
            return;
        }

        $this->context->buildViolation($constraint->alreadyVotedForPollMessage)->addViolation();
    }
}