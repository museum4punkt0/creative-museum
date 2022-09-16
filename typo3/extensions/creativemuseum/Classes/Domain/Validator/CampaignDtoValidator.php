<?php

namespace JWIED\Creativemuseum\Domain\Validator;

use JWIED\Creativemuseum\Domain\Model\Dto\CampaignDto;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class CampaignDtoValidator extends AbstractValidator
{
    protected function isValid($campaignDto)
    {
        if (! $campaignDto instanceof CampaignDto) {
            $this->addError('The given object must be an instance of CampaignDto', 1663244022);
        }

        if ($campaignDto->getFeedbackOptions()->count() < 2) {
            $this->addError('There must be at least 2 feedback options', 1663244893);
        }

        if ($campaignDto->getFeedbackOptions()->count() > 5) {
            $this->addError('There must be 5 feedback options at most', 1663244932);
        }
    }
}