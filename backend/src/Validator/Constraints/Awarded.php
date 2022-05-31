<?php

namespace App\Validator\Constraints;

use App\Validator\AwardedValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Awarded extends Constraint
{
    public $giverNotCampaignMember = 'Giver is not a campaign member';

    public $receiverNotCampaignMember = 'Receiver is not a campaign member';

    public $notEnoughPoints = 'Giver has not enough points';

    public $alreadyAwarded = 'Giver has already presented the award';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return AwardedValidator::class;
    }
}