<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace App\Validator;

use App\Entity\Award;
use App\Entity\Awarded;
use App\Entity\Campaign;
use App\Entity\User;
use App\Repository\AwardedRepository;
use App\Repository\CampaignMemberRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class AwardedValidator extends ConstraintValidator
{
    private CampaignMemberRepository $campaignMemberRepository;

    private AwardedRepository $awardedRepository;

    public function __construct(CampaignMemberRepository $campaignMemberRepository, AwardedRepository $awardedRepository)
    {
        $this->campaignMemberRepository = $campaignMemberRepository;
        $this->awardedRepository = $awardedRepository;
    }

    /**
     * @param $value
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof Awarded) {
            return;
        }

        $continue = true;

        /**
         * @var Awarded                            $value
         * @var \App\Validator\Constraints\Awarded $constraint
         */
        if (!$this->isCampaignMember($value->getAward()->getCampaign(), $value->getGiver())) {
            $this->context->buildViolation($constraint->giverNotCampaignMember)->addViolation();
            $continue = false;
        }

        if (!$this->isCampaignMember($value->getAward()->getCampaign(), $value->getWinner())) {
            $this->context->buildViolation($constraint->receiverNotCampaignMember)->addViolation();
            $continue = false;
        }

        if (!$continue) {
            return;
        }

        if ($value->getAward()->getPrice() > $this->getCampaignScore($value->getAward()->getCampaign(), $value->getGiver())) {
            $this->context->buildViolation($constraint->notEnoughPoints)->addViolation();

            return;
        }

        if ($this->hasAlreadyAwarded($value->getAward(), $value->getGiver())) {
            $this->context->buildViolation($constraint->alreadyAwarded)->addViolation();
        }
    }

    /**
     * @param Awarded $awarded
     */
    private function isCampaignMember(Campaign $campaign, User $user): bool
    {
        $result = $this->campaignMemberRepository->findBy([
            'user' => $user->getId(),
            'campaign' => $campaign->getId(),
        ]);

        return !empty($result);
    }

    private function getCampaignScore(Campaign $campaign, User $user): int
    {
        $result = $this->campaignMemberRepository->findOneBy([
            'user' => $user->getId(),
            'campaign' => $campaign->getId(),
        ]);

        return $result->getScore();
    }

    private function hasAlreadyAwarded(Award $award, User $user): bool
    {
        $result = $this->awardedRepository->findBy([
            'giver' => $user->getId(),
            'award' => $award->getId(),
        ]);

        return !empty($result);
    }
}
