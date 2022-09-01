<?php

namespace App\Validator\Constraints;

use App\Validator\CampaignInactiveValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CampaignInactive extends Constraint
{
    public string $campaignIsInactive = 'Campaign is inactive!';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return CampaignInactiveValidator::class;
    }
}