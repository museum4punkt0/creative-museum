<?php

namespace App\Validator;

use App\Entity\Post;
use App\Entity\PostFeedback;
use App\Entity\Vote;
use App\Entity\Awarded;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
class CampaignInactiveValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if ($value instanceof Post && !$value->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        } else if ($value instanceof PostFeedback && !$value->getPost()->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        } else if ($value instanceof Vote && !$value->getPost()->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        } else if ($value instanceof Awarded && !$value->getAward()->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        }
    }

    private function buildCampaignInactiveViolation(Constraint $constraint)
    {
        $this->context->buildViolation($constraint->campaignIsInactive)->addViolation();
    }
}