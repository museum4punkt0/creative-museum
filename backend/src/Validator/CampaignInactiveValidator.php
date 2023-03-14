<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\Awarded;
use App\Entity\Post;
use App\Entity\PostFeedback;
use App\Entity\Vote;
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
        } elseif ($value instanceof PostFeedback && !$value->getPost()->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        } elseif ($value instanceof Vote && !$value->getPost()->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        } elseif ($value instanceof Awarded && !$value->getAward()->getCampaign()->getActive()) {
            $this->buildCampaignInactiveViolation($constraint);
        }
    }

    private function buildCampaignInactiveViolation(Constraint $constraint)
    {
        $this->context->buildViolation($constraint->campaignIsInactive)->addViolation();
    }
}
