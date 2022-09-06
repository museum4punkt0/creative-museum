<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator\Constraints;

use App\Validator\AwardedValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Awarded extends Constraint
{
    public $giverNotCampaignMember = 'Giver is not a campaign member';

    public $canNotAwardSelf = 'You can not give an award to yourself';

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
